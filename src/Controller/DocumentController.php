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

class DocumentController extends AbstractController
{
    /**
     * @param Request $request
     * @param Document $document
     * @Route("/documents/{document}", name="documents")
     */
    public function edit(Request $request, Document $document)
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $document->setScanStatus(Document::STATUS_SUCCESS);
            
            $this->getDoctrine()->getManager()->persist($document);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('documents', ['document' => $document->getDocumentId()]);
        } elseif ($document->getScanStatus() === Document::STATUS_PROCESSING) {
            $billsDir = $this->getParameter('bills_directory') . '/';
            $slicesDir = $this->getParameter('slices_directory') . '/';
            
            $billImagePath = $billsDir . $document->getOriginalFile();
            
            $isScanError = false;
            $fileSystem = new Filesystem();
            $slicer = new ImageSlicer($billImagePath, $slicesDir);
            $imageSlices = $slicer->run($document->getScanParameter());
            
            foreach ($imageSlices as $slice) {
                $reader = new TesseractOCR();
                $reader->image($slice['file_path']);
                $reader->lang('lit');
                
                $imageParser = new ImageParser($reader);
                try {
                    $text = $imageParser->run($slice);
                } catch (\Exception $e) {
                    $isScanError = true;
                    $fileSystem->remove($slice['file_path']);
                    continue;
                }
                
                $fileSystem->remove($slice['file_path']);
                
                if (isset($slice['key']) && is_callable([$document, 'set' . $slice['key']])) {
                    $document->{'set' . $slice['key']}($text);
                }
            }
            
            $document->setScanStatus($isScanError ? Document::STATUS_ERROR : Document::STATUS_SUCCESS);
            
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
    public function list(Request $request)
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
        
        return $this->render(
            'documents/list.html.twig',
            [
                'documents' => $pagerFanta->getCurrentPageResults(),
                'pager' => $pagerFanta,
            ]
        );
    }
    
    /**
     * @param Request $request
     * @param Document $document
     * @Route("/documents/download/{document}", name="document_download")
     */
    
    public function documentExport(Request $request, Document $document)
    {
        $seriesNumber = $document->getInvoiceSeries() . "-" . $document->getInvoiceNumber();
        $buyerName = $document->getInvoiceBuyerName();
        $buyerAddres = $document->getInvoiceBuyerAddress();
        $buyerCode = $document->getInvoiceBuyerCode();
        $vatCode = $document->getInvoiceBuyerVatCode();
        $date = date_format($document->getInvoiceDate(), 'Y-m-d');
        $totalPrice = $document->getInvoiceTotal();
        
        header('Content-type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=invoice.csv');
        
        $fp = fopen('php://output', 'w');
        $list = [$date, $seriesNumber, $buyerName, $buyerCode, $vatCode, $buyerAddres, $totalPrice];
        fputcsv($fp, [
            'Sąsk. data', 'Serija', 'Klientas', 'Į.k.', 'PVM k.', 'Adresas', 'Viso',
        ]);
        
        fputcsv($fp, $list);
        fclose($fp);
        
        return $this->render('documents/download.html.twig');
        // $this->redirectToRoute('documents_list');
    }
}
