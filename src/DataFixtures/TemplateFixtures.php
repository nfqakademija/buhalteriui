<?php

namespace App\DataFixtures;

use App\Entity\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TemplateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /**
         * php bin/console doctrine:fixtures:load --append
         */
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
        
        $template = new Template();
        $template->setTitle('Kesko Senukai');
        $template->setParameters($config);
        $manager->persist($template);

        $manager->flush();
    }
}
