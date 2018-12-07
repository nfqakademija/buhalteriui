<?php
/**
 * Created by PhpStorm.
 * User: Matas
 * Date: 2018-12-02
 * Time: 20:53
 */

namespace App\Service;


use thiagoalessio\TesseractOCR\TesseractOCR;

class ImageParser
{
    
    protected $reader;
    
    public function __construct(TesseractOCR $reader)
    {
        $this->reader = $reader;
    }
    
    public function run(array $slice)
    {
        if (isset($slice['tesseract'])) {
            foreach ($slice['tesseract'] as $method => $argument) {
                $this->reader->{$method}($argument);
            }
        }
        $text = $this->reader->run();
        
        if (isset($slice['methods'])) {
            foreach ($slice['methods'] as $method => $argument) {
                $text = $this->{$method}($text, $argument);
            }
        }
        
        return $text;
    }
    
    protected function parse($text, $type): string
    {
        $matches = [];
        
        switch ($type) {
            case 'invoice_date':
                if (preg_match('#:\s+(?<date>[0-9.]+)$#i', trim($text), $matches)) {
                    return str_replace('.', '-', $matches['date']);
                }
                break;
            
            case 'invoice_series':
                //Serija SS Nr. 12345
                if (preg_match('#Serija\s+(?<series>[A-Z0-9]{2})\s+Nr\.?\s(?<number>[0-9]+)$#i', trim($text), $matches)) {
                    return $matches['series'];
                }
                break;
            
            case 'invoice_number':
                //Serija SS Nr. 12345
                if (preg_match('#Serija\s+(?<series>[A-Z0-9]{2})\s+Nr\.?\s(?<number>[0-9]+)$#i', trim($text), $matches)) {
                    return $matches['number'];
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
    
    protected function return($text, $type)
    {
        switch ($type) {
            case 'date_time':
                $text = new \DateTime($text);
                break;
        }
        
        return $text;
    }
}