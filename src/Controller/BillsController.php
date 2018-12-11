<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BillType;
use App\Entity\Document;

class BillsController extends AbstractController
{
    /**
     * @Route("/", name="bills")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(BillType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $document = new Document();
            
            $file = $form->get('bill')->getData();
            
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            
            $file->move(
                $this->getParameter('bills_directory'),
                $fileName
            );
            
            $document->setOriginalFile($fileName);
            
            $this->getDoctrine()->getManager()->persist($document);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('documents', ['document' => $document->getDocumentId()]);
        }
        
        return $this->render('bills/index.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
