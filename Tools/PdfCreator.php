<?php

namespace Tactics\InvoiceBundle\Tools;

use ZendPdf\PdfDocument;
use Tactics\InvoiceBundle\Model\Invoice;

class PdfCreator
{
  public static function generatePdf(Invoice $invoice)
  {
    $pdf = new PdfDocument();
    
    return $pdf;
  }
  
  public static function stream($pdf, $filename)
  {
    $response = \sfContext::getInstance()->getResponse();

    $response->setContentType('application/pdf; charset=utf-8');
    $response->setHttpHeader('Content-Language', 'nl');
    $response->addVaryHttpHeader('Accept-Language');
    $response->addCacheControlHttpHeader('no-cache');
    $response->setHttpHeader('Content-Disposition', 'attachment; filename=' . $filename);
    
    echo $pdf->render();    
  }
  
}

