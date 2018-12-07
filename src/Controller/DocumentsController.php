<?php

namespace App\Controller;

use App\Service\ImageParser;
use App\Service\ImageSlicer;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DocumentType;
use App\Entity\Document;
use thiagoalessio\TesseractOCR\TesseractOCR;

class DocumentsController extends AbstractController
{
    /**
     * @param Request $request
     * @param Document $document
     * @Route("/documents/{document}", name="documents")
     */
    public function editBills(Request $request, Document $document)
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($document);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('documents', ['document' => $document->getDocumentId()]);
        } else if ($document->getScanStatus() === Document::STATUS_PROCESSING) {
            $projectDir = 'uploads/';
            $billsDir = $projectDir . 'bills/';
            $slicesDir = $projectDir . 'slices/';
            
            $billImagePath = $billsDir . $document->getOriginalFile();
            
            $config = [
                [
                    'key' => 'invoiceDate',
                    'box' => ['x' => 1180, 'y' => 31, 'width' => 175, 'height' => 50],
                    'methods' => [
                        'parse' => 'invoice_date',
                        'return' => 'date_time',
                    ],
                ],
                [
                    'key' => 'invoiceSeries',
                    'box' => ['x' => 170, 'y' => 190, 'width' => 1140, 'height' => 75],
                    'methods' => [
                        'parse' => 'invoice_series',
                    ],
                ],
                [
                    'key' => 'invoiceNumber',
                    'box' => ['x' => 170, 'y' => 190, 'width' => 1140, 'height' => 75],
                    'methods' => [
                        'parse' => 'invoice_number',
                    ],
                ],
                [
                    'key' => 'invoiceBuyerName',
                    'box' => ['x' => 50, 'y' => 540, 'width' => 500, 'height' => 40],
                ],
                [
                    'key' => 'invoiceBuyerAddress',
                    'box' => ['x' => 50, 'y' => 580, 'width' => 500, 'height' => 33],
                ],
                [
                    'key' => 'invoiceBuyerVatCode',
                    'box' => ['x' => 50, 'y' => 613, 'width' => 500, 'height' => 28],
                    'methods' => [
                        'parse' => 'vat_code',
                    ],
                ],
                [
                    'key' => 'invoiceBuyerCode',
                    'box' => ['x' => 50, 'y' => 641, 'width' => 500, 'height' => 30],
                    'methods' => [
                        'parse' => 'company_code',
                    ],
                ],
                [
                    'key' => 'invoiceTotal',
                    'box' => ['x' => 1320, 'y' => 504, 'width' => 235, 'height' => 39],
                    'tesseract' => [
                        'whitelist' => array_merge(range(0, 9), ['.']),
                    ],
                    'methods' => [
                        'parse' => 'currency',
                    ],
                ],
            ];
            
            $fileSystem = new Filesystem();
            $slicer = new ImageSlicer($billImagePath, $slicesDir);
            $imageSlices = $slicer->run($config);
            
            foreach ($imageSlices as $slice) {
                $reader = new TesseractOCR();
                $reader->image($slice['file_path']);
                $reader->lang('lit');
                
                $imageParser = new ImageParser($reader);
                $text = $imageParser->run($slice);
                
                $fileSystem->remove($slice['file_path']);
                
                if (isset($slice['key']) && is_callable([$document, 'set' . $slice['key']])) {
                    $document->{'set' . $slice['key']}($text);
                }
            }
            
            $document->setScanStatus(Document::STATUS_SUCCESS);
            
            $this->getDoctrine()->getManager()->persist($document);
            $this->getDoctrine()->getManager()->flush();
            
            $form->setData($document);
        }
        
        return $this->render(
            'documents/index.html.twig',
            [
                'file' => $document->getOriginalFile(),
                'document' => $document,
                'form' => $form->createView(),
            ]
        );
    }
    
    /**
     * @param Request $request
     * @Route("/documents", name="document_list")
     */
    public function documentList(Request $request)
    {
        $page = $request->query->get('page', 1);
        $queryBuilder = $this->getDoctrine()->getRepository(Document::class)->findAllQueryBuilder();
        $queryBuilder->orderBy('Document.documentId', 'DESC');
        $adapter = new DoctrineORMAdapter($queryBuilder);
        
        $pagerFanta = new Pagerfanta($adapter);
        $pagerFanta->setMaxPerPage(10);
        
        try {
            $pagerFanta->setCurrentPage($page);
        } catch (NotValidCurrentPageException $e) {
            throw new NotFoundHttpException();
        }
        
        $documents = [];
        foreach($pagerFanta->getCurrentPageResults() as $document){
            $documents[] = $document;
        }
        
        return $this->render(
            'documents/list.html.twig',
            [
                'documents' => $pagerFanta->getCurrentPageResults(),
                'pager' => $pagerFanta,
            ]
        );
    }
}
