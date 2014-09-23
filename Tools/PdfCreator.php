<?php

namespace Tactics\InvoiceBundle\Tools;

use ZendPdf\PdfDocument;
use ZendPdf\Page;
use ZendPdf\Font;
use ZendPdf\Color\Html;
use ZendPdf\Image;
use Tactics\InvoiceBundle\Model\Invoice;


class PdfCreator
{
  public function createPdf(Invoice $invoice)
  {
    \Misc::use_helper('Helper', 'Number');
    $pdf = PdfDocument::load(SF_ROOT_DIR . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'ttInvoice' . DIRECTORY_SEPARATOR . 'template.pdf');
    // print marge van 10/13 mm Max dimensions A4 = 210mm x 297mm / 595.28 Pts 841.89 Pts

    $eindtotaal = 0;

    //standaard gebruikt font.
    $font = Font::fontWithName(Font::FONT_HELVETICA);
    $overschrijvingsFont = Font::fontWithName(Font::FONT_COURIER_BOLD);

    //Currency, euro standaard
    $currency = $invoice->getCurrency() ? $invoice->getCurrency() : 'EUR';

    ///////////
    ////////// Kleuren:
    /////////
    $cAlert = Html::color('#FF2A00');
    $cDraw = Html::color('#424242');
    $cText = Html::color('#000000');
    $cLightText = Html::color('#000000');
    $cWhite = Html::color('#FFFFFF');

    ///////////
    ////////// Header:
    /////////
    // Onze gegevens
    $pdf->pages[0]
      ->setFillColor($cText)
      ->setFont( $font , 14 )
      ->drawText( 'APB SPORT', $this->mmToPoints(13), 805 );
    $pdf->pages[0]
      ->setFont( $font , 10 )
      ->drawText( 'Boomgaardstraat 22 - 2600 Berchem', $this->mmToPoints(13), 790 )
      ->drawText( 'Tel. 03 2406270 Fax. 032406299', $this->mmToPoints(13), 779 )
      ->drawText( 'BTW BE 0820.377.203' , $this->mmToPoints(13), 768 )
      ->drawText( 'Bank 731-0077943-27' , $this->mmToPoints(13), 757 )
      ->drawText( 'IBAN BE48 7310 0779 4327 BIC KREDBEBB' , $this->mmToPoints(13), 746 );

    //Ontvanger
    $costumer = $invoice->getCustomer();
    $pdf->pages[0]
      ->setFont( $font , 14 )
      ->drawText( $costumer->getNaam(), 350, 730 );
    $pdf->pages[0]
      ->setFont( $font , 10 )
      ->drawText( $costumer->getStraat() . ' ' . $costumer->getNummer() . ' ' . $costumer->getBus(), 350, 685 )
      ->drawText( $costumer->getPostcode() . ' ' . $costumer->getGemeente() . ' ' . $costumer->getLandId(), 350, 674 )
      ->drawText( 'IBAN ' . $costumer->getRekeningnummerIban() , 350, 663);

    // Factuur gegevens
    $pdf->pages[0]
      ->setFont( $font , 12 )
      ->setFillColor($cLightText)
      ->drawText( 'Factuur ' . $invoice->getNumber(), $this->mmToPoints(16), 641 ); //Factuurnummer
    $pdf->pages[0]
      ->setFont( $font, 10)
      ->drawText( $invoice->getDate('d-m-Y'), 480, 588 ) // factuurdatum
      ->drawText( $invoice->getNumber(), 390, 588 ); // factuurnummer (Doc. Nr.)
      //ontbreekt nog klantcode?

    ///////////
    ////////// Items:
    /////////
    //Items
    $i = 0;
    $items = $invoice->getItems();
    foreach($items as $item)
    {
      $pdf->pages[0]
        ->setFont( $font, 10 )
        ->setFillColor( $cText )
        ->drawText( $item->getDescription(), 50, 540 - (11 * $i) )
        ->drawText( $item->getQuantity(), 250, 540 - (11 * $i) )
        ->drawText( format_currency($item->getUnitPrice(), $currency), 340, 540 - (11 * $i), 'UTF-8' )
        ->drawText( format_currency($item->getPriceExVat(), $currency), 410, 540 - (11 * $i), 'UTF-8' )
        ->drawText( $item->getVat()->getPercentage() . '%', 520, 540 - (11 * $i) );
      $i++;
    }

    //Total
    $eindtotaal = $invoice->getTotal() + $invoice->getVat();
    $pdf->pages[0]
      ->drawText( format_currency($invoice->getTotal(), $currency), 490, 381, 'UTF-8' ) // totaal zonder btw
      ->drawText( format_currency($invoice->getVat(), $currency), 490, 365, 'UTF-8' ) //totaal BTW
      ->drawText( format_currency($eindtotaal, $currency), 490, 346, 'UTF-8' ) //Eindtotaal
      ->drawText( $invoice->getDateDue('d-m-Y'), 120, 346 ) // vervaldag
      ->drawText( $invoice->getStructuredCommunication(), 120 ,310 ); // gestructureerde mededeling
      // ontbreekt nog totalen voor elke BTW apart

    ///////////
    ////////// Footer (overschrijvingsformulier):
    /////////
    $pdf->pages[0]
      ->setFont( $font, 10 )
      ->drawText( format_currency($eindtotaal), 500, 215, 'UTF-8' ) //totaalbedrag
      ->drawText( $costumer->getNaam(), 130, 170 ) //ontvanger
      ->drawText( $costumer->getStraat() . ' ' . $costumer->getNummer() . ' ' . $costumer->getBus(), 130, 159 )
      ->drawText( $costumer->getPostcode() . ' ' . $costumer->getGemeente() . ' ' . $costumer->getLandId(), 130, 148 )
      ->setFont( $overschrijvingsFont, 12 )
      ->drawText( 'B E 4 8 7 3 1 0 0 7 7 9 4 3 2 7' , 104, 121 ) // IBAN Provincie
      ->drawText( 'K R E D B E B B' , 104, 98 ) // BIC Provincie
      ->setFont( $font , 10 )
      ->drawText( 'APB SPORT', 130, 70 ) // gegevens Provincie
      ->drawText( 'Boomgaardstraat 22', 130, 59 )
      ->drawText( '2600 Berchem (Antwerpen) BE', 130, 48 )
      ->drawText( $invoice->getStructuredCommunication(), 104 ,25 ); // gestructureerde mededeling

    return $pdf;
  }

///////////
////////// Tools:
/////////
  // Create wrapped text. (multiline)
  private function drawTextArea($pdf, $text, $pos_x, $pos_y, $height, $length = 0, $offset_x = 0, $offset_y = 0)
  {
    $x = $pos_x + $offset_x;
    $y = $pos_y + $offset_y;

    if ($length != 0) {
      $text = wordwrap($text, $length, "\n", false);
    }
    $token = strtok($text, "\n");

    while ($token != false) {
      $pdf->pages[0]->drawText($token, $x, $y);
      $token = strtok("\n");
      $y -= $height;
    }
  }

  //Converts mm to points for coordinates.
  private function mmToPoints( $mm )
  {
    return $mm / 25.4 * 72;
  }
}

