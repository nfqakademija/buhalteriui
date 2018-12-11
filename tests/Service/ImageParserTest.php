<?php

namespace App\Tests\Service;

use App\Service\ImageParser;
use PHPUnit\Framework\TestCase;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ImageParserTest extends TestCase
{
    public function testParseInvoiceDate()
    {
        $reader = $this->createMock(TesseractOCR::class);
        
        $calculator = new ImageParser($reader);
        $result = $calculator->parse('Tekstas: 2018.12.05', 'invoice_date');
        
        $this->assertEquals('2018-12-05', $result);
    }
    
    /**
     * @param $expected
     * @param $text
     *
     * @dataProvider parseInvoiceSeriesProvider
     */
    public function testParseInvoiceSeries($expected, $text)
    {
        $reader = $this->createMock(TesseractOCR::class);
        
        $calculator = new ImageParser($reader);
        $result = $calculator->parse($text, 'invoice_series');
        
        $this->assertEquals($expected, $result);
    }
    
    /**
     * @param $expected
     * @param $text
     *
     * @dataProvider parseInvoiceSeriesNumber
     */
    public function testParseInvoiceNumber($expected, $text)
    {
        $reader = $this->createMock(TesseractOCR::class);
        
        $calculator = new ImageParser($reader);
        $result = $calculator->parse($text, 'invoice_number');
        
        $this->assertEquals($expected, $result);
    }
    
    public function parseInvoiceSeriesProvider()
    {
        return [
            ['SS', 'Serija SS Nr. 12345'],
            ['AB', 'Serija AB Nr. 12345'],
            ['', 'Dokumentas AB Nr. 12345'],
            ['', 'Serija AB Nr. A345'],
        ];
    }
    
    public function parseInvoiceSeriesNumber()
    {
        return [
            ['12345', 'Serija SS Nr. 12345'],
            ['12345', 'Serija AB Nr. 12345'],
            ['', 'Dokumentas AB Nr. 12345'],
            ['', 'Serija Nr. A345'],
        ];
    }
}