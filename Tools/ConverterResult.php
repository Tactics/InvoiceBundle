<?php

namespace Tactics\InvoiceBundle\Tools;

class ConverterResult
{
    private $filename;
    private $mimeType;
    private $output;
    
    /**
     * Constructor
     * 
     * @param string $filename
     * @param string $mimeType
     * @param string $output
     */
    public function __construct($filename, $mimeType, $output) {
        $this->filename = $filename;
        $this->mimeType = $mimeType;
        $this->output = $output;
    }
    
    public function output() {
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $this->mimeType . '; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $this->filename);
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');        
        
        echo $this->output;
        
        exit();
    }
}

