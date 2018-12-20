<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceExampleController extends AbstractController
{
    /**
     * @Route("/invoice/example", name="invoice_example")
     */
    public function index()
    {
        return $this->render('invoice_example/index.html.twig');
    }
}
