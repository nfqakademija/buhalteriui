<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ImageSlicer;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Document;

class ImageSlicerController extends Controller
{
    /**
     * @param Request $request
     * @param Document $document
     * @Route("/image/parser/{document}", name="image_parser")
     */
    public function index(Request $request, Document $document)
    {
        $projectDir = 'uploads/';
        $billsDir = $this->getParameter('bills_directory') . '/';
        $slicesDir = $this->getParameter('slices_directory') . '/';
        
        $billImagePath = $billsDir . $document->getOriginalFile();
        
        //*
        $x_shift = 0;
        $y_shift = 0;
        /*/
        $x_shift = -20;
        $y_shift = -30;
        //*/
        
        $config = [
            [
                'key' => 'invoice_date',
                'box' => ['x' => $x_shift + 1180, 'y' => $y_shift + 31, 'width' => 175, 'height' => 50],
                'methods' => [
                    'parse' => 'invoice_date',
                ],
            ],
            [
                'key' => 'series',
                'box' => ['x' => $x_shift + 170, 'y' => $y_shift + 190, 'width' => 1140, 'height' => 75],
                'methods' => [
                    'parse' => 'series',
                ],
            ],
            [
                'key' => 'buyer_name',
                'box' => ['x' => $x_shift + 50, 'y' => $y_shift + 540, 'width' => 500, 'height' => 40],
            ],
            [
                'key' => 'buyer_address',
                'box' => ['x' => $x_shift + 50, 'y' => $y_shift + 580, 'width' => 500, 'height' => 33],
            ],
            [
                'key' => 'buyer_vat_code',
                'box' => ['x' => $x_shift + 50, 'y' => $y_shift + 613, 'width' => 500, 'height' => 28],
                'methods' => [
                    'parse' => 'vat_code',
                ],
            ],
            [
                'key' => 'buyer_code',
                'box' => ['x' => $x_shift + 50, 'y' => $y_shift + 641, 'width' => 500, 'height' => 30],
                'methods' => [
                    'parse' => 'company_code',
                ],
            ],
            /*
            [
                'key' => 'detailed_information',
                'box' => ['x' => $x_shift+40, 'y' => $y_shift+912, 'width' => 1500, 'height' => 110],
            ],
            [
                'key' => 'payment_information',
                'box' => ['x' => $x_shift+860, 'y' => $y_shift+415, 'width' => 700, 'height' => 210],
            ],
            [
                'key' => 'payment_information_part1',
                'box' => ['x' => $x_shift+860, 'y' => $y_shift+415, 'width' => 700, 'height' => 51],
            ],
            [
                'key' => 'payment_information_part2',
                'box' => ['x' => $x_shift+860, 'y' => $y_shift+466, 'width' => 700, 'height' => 38],
            ],
            */
            [
                'key' => 'invoice_total',
                'box' => ['x' => $x_shift + 1320, 'y' => $y_shift + 504, 'width' => 235, 'height' => 39],
                'tesseract' => [
                    'whitelist' => array_merge(range(0, 9), ['.']),
                ],
                'methods' => [
                    'parse' => 'currency',
                ],
            ],
            /*
            [
                'key' => 'totals',
                'box' => ['x' => $x_shift+1020, 'y' => $y_shift+1085, 'width' => 400, 'height' => 170],
            ],
            [
                'key' => 'totals_part1',
                'box' => ['x' => $x_shift+1020, 'y' => $y_shift+1095, 'width' => 400, 'height' => 30],
            ],
            [
                'key' => 'totals_part2',
                'box' => ['x' => $x_shift+1020, 'y' => $y_shift+1128, 'width' => 400, 'height' => 36],
            ],
            [
                'key' => 'totals_part3',
                'box' => ['x' => $x_shift+1020, 'y' => $y_shift+1164, 'width' => 400, 'height' => 36],
            ],
            */
            [
                'key' => 'invoice_total_2',
                'box' => ['x' => $x_shift + 1270, 'y' => $y_shift + 1200, 'width' => 150, 'height' => 36],
                'tesseract' => [
                    'whitelist' => array_merge(range(0, 9), ['.']),
                ],
                'methods' => [
                    'parse' => 'currency',
                ],
            ],
        ];
        
        
        $slicer = new ImageSlicer($billImagePath, $slicesDir);
        $imageSlices = $slicer->run($config);
        $imageSlicesReturn = [];
        
        foreach ($imageSlices as $slice) {
            $reader = new TesseractOCR();
            $reader->image($slice['file_path']);
            $reader->lang('lit');
            if (isset($slice['tesseract'])) {
                foreach ($slice['tesseract'] as $method => $argument) {
                    $reader->{$method}($argument);
                }
            }
            $slice['text'] = $reader->run();
            $slice['image_path'] = $projectDir . $slice['file_name'];
            
            if (isset($slice['methods'])) {
                foreach ($slice['methods'] as $method => $argument) {
                    $slice['text'] = $this->{$method}($slice['text'], $argument);
                }
            }
            $imageSlicesReturn[$slice['key']] = array_intersect_key($slice, ['key' => 1, 'box' => 1, 'image_path' => 1, 'text' => 1]);
        }
        
        if ($request->get('return') === 'html') {
            return $this->render('image_slicer/index.html.twig', [
                'bill_image_path' => $billImagePath,
                'slices' => $imageSlicesReturn,
            ]);
        }
        
        return $this->json($imageSlicesReturn);
    }
    
    protected function parse($text, $type)
    {
        $matches = [];
        
        switch ($type) {
            case 'invoice_date':
                if (preg_match('#:\s+(?<date>[0-9.]+)$#i', trim($text), $matches)) {
                    return $matches['date'];
                }
                break;
            
            case 'series':
                //Serija SS Nr. 12345
                if (preg_match('#Serija\s+(?<series>[A-Z]{2})\s+Nr\.?\s(?<number>[0-9]+)$#i', trim($text), $matches)) {
                    return $matches['series'] . '-' . $matches['number'];
                }
                break;
            
            case 'vat_code':
                if (preg_match('#:\s+(?<code>LT[0-9]+)$#i', trim($text), $matches)) {
                    return $matches['code'];
                }
                break;
            
            case 'company_code':
                //Įmonės kodas: 22285219
                if (preg_match('#:\s+(?<code>[0-9]+)$#i', trim($text), $matches)) {
                    return $matches['code'];
                }
                break;
            
            case 'currency':
                if (strpos($text, '.') === false) {
                    $text = substr_replace($text, '.', -2, 0);
                }
                break;
        }
        
        return $text;
    }
    
    /**
     * @Route("/image/version", name="image_version")
     */
    public function version()
    {
        return $this->json([
            'v' => 1,
            'project_dir' => $this->get('kernel')->getProjectDir(),
        ]);
    }
}