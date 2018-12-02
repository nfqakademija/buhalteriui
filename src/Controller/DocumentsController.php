<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DocumentType;
use App\Entity\Document;

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

        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($document);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('documents', ['document' => $document->getDocumentId()]);
        }

        return $this->render(
            'documents/index.html.twig',
            [
                'file' => $document->getOriginalFile(),
                'form' => $form->createView(),
            ]
        );
    }
}
