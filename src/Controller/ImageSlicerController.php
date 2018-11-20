<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ImageSlicer;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ImageSlicerController extends AbstractController
{
    /**
     * @Route("/image/slicer", name="image_slicer")
     */
    public function index()
    {
        //$webPath = $this->get('kernel')->getProjectDir() . '/public/';
        $webPath = 'uploads/';
        $imagePath = $webPath . 'senukai-invoice-example.jpg';
        $slicer = new ImageSlicer($imagePath, $webPath . 'slices/');
        
        $config = [
            [
                'key' => 'series',
                'box' => ['x' => 170, 'y' => 190, 'width' => 1140, 'height' => 75],
            ],
            [
                'key' => 'buyer',
                'box' => ['x' => 50, 'y' => 540, 'width' => 500, 'height' => 140],
            ],
            [
                'key' => 'detailed_information',
                'box' => ['x' => 40, 'y' => 912, 'width' => 1500, 'height' => 110],
            ],
            [
                'key' => 'payment_information',
                'box' => ['x' => 860, 'y' => 415, 'width' => 700, 'height' => 210],
            ],
            [
                'key' => 'totals',
                'box' => ['x' => 1020, 'y' => 1085, 'width' => 400, 'height' => 170],
            ],
        ];
        
        $imageSlices = $slicer->run($config);
        $reader = new TesseractOCR();
        
        foreach ($imageSlices as &$slice) {
            $reader->image($slice['file_path']);
            $reader->lang('lit');
            $slice['text'] = $reader->run();
        }
        
        return $this->render('image_slicer/index.html.twig', [
            'slices' => $imageSlices,
        ]);
    }
    
    /**
     * @Route("/image/test", name="image_test")
     */
    public function phpinfo()
    {
        phpinfo();
        exit;
    }
}
