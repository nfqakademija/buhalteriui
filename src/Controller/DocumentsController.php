<?php

namespace App\Controller;

use App\Entity\Template;
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
            $billsDir = $this->getParameter('bills_directory') . '/';
            $slicesDir = $this->getParameter('slices_directory') . '/';
            
            $billImagePath = $billsDir . $document->getOriginalFile();
            
            $template = $this->getDoctrine()
                ->getRepository(Template::class)
                ->find($this->getParameter('template_id'));
            
            $isScanError = false;
            $fileSystem = new Filesystem();
            $slicer = new ImageSlicer($billImagePath, $slicesDir);
            $imageSlices = $slicer->run($template->getParameters());
            
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
        foreach ($pagerFanta->getCurrentPageResults() as $document) {
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
