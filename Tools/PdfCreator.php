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
    $pdf = new PdfDocument();
    $pdf->pages[0] = new Page( Page::SIZE_A4 ); // print marge van 10/13 mm Max dimensions A4 = 210mm x 297mm / 595.28 Pts 841.89 Pts

    $eindtotaal = 0;

    //standaard gebruikt font.
    $font = Font::fontWithName(Font::FONT_HELVETICA);

    //logo
    $logo = Image::imageWithPath('ttInvoice/images/provant-logo.png');

    //Currency, euro standaard
    $currency = $invoice->getCurrency() ? $invoice->getCurrency() : 'EUR';

    ///////////
    ////////// Kleuren:
    /////////
    $cAlert = Html::color('#FF2A00');
    $cDraw = Html::color('#424242');
    $cText = Html::color('#5C5C5C');
    $cLightText = Html::color('#ADADAD');
    $cWhite = Html::color('#FFFFFF');

    ///////////
    ////////// Header:
    /////////
    //logo
    $pdf->pages[0]
      ->drawImage( $logo, $this->mmToPoints(13), 750, $this->mmToPoints(13) + 190, 813);

    // Factuur + Factuur datum
    $pdf->pages[0]
      ->setFont( $font , 12 )
      ->setFillColor($cLightText)
      ->drawText( 'Factuur ' . $invoice->getNumber(), $this->mmToPoints(16), 730 );
    $pdf->pages[0]
      ->setFont( $font, 10)
      ->drawText( $invoice->getDate('d-m-Y'), $this->mmToPoints(16), 715 );

    // Onze gegevens
    $pdf->pages[0]
      ->setFillColor($cText)
      ->setFont( $font , 14 )
      ->drawText( 'APB SPORT', 300, 780 );
    $pdf->pages[0]
      ->setFont( $font , 10 )
      ->drawText( 'Boomgaardstraat 22 - 2600 Berchem', 300, 755 )
      ->drawText( 'Tel. 03 2406270 Fax. 032406299', 300, 740 )
      ->drawText( 'BTW BE 0820.377.203' , 300, 725)
      ->drawText( 'Bank 731-0077943-27' , 300, 710)
      ->drawText( 'IBAN BE48 7310 0779 4327 BIC KREDBEBB' , 300, 695);

    //Ontvanger
    $costumer = $invoice->getCustomer();
    $pdf->pages[0]
      ->setFont( $font , 14 )
      ->drawText( $costumer->getNaam(), 300, 665 );
    $pdf->pages[0]
      ->setFont( $font , 10 )
      ->drawText( $costumer->getStraat() . ' ' . $costumer->getNummer() . ' ' . $costumer->getBus(), 300, 640 )
      ->drawText( $costumer->getPostcode() . ' ' . $costumer->getGemeente() . ' ' . $costumer->getLandId(), 300, 625 )
      ->drawText( 'IBAN ' . $costumer->getRekeningnummerIban() , 300, 610);

    ///////////
    ////////// Items:
    /////////
    //Items Header
    $pdf->pages[0]
      ->setLineColor( $cDraw )
      ->setFillColor( $cDraw )
      ->drawRectangle( 0, 540, 510, 570 );
    $pdf->pages[0]
      ->setFont( $font, 10 )
      ->setFillColor( $cWhite )
      ->setLineColor( $cLightText )
      ->setLineWidth( 0.5 )
      ->drawText( 'Omschrijving ' , $this->mmToPoints(13) , 550 )
      ->drawLine( 200, 540, 200, 570 )
      ->drawText( '#', 210, 550 )
      ->drawLine( 230, 540, 230, 570 )
      ->drawText( 'Prijs/stuk' , 240, 550 )
      ->drawLine( 290 , 540, 290, 570 )
      ->drawText( 'Prijs (Excl)' , 300, 550 )
      ->drawLine( 360, 540, 360, 570 )
      ->drawText( 'BTW (%)', 370, 550 )
      ->drawLine( 420, 540, 420, 570 )
      ->drawText( 'Prijs (Incl)', 450, 550 );

    //Items
    $i = 0;
    $items = $invoice->getItems();
    foreach($items as $item)
    {
      $pdf->pages[0]
        ->drawLine( 200, 510 - (30 * $i), 200, 540 - (30 * $i) )
        ->drawLine( 230, 510 - (30 * $i), 230, 540 - (30 * $i) )
        ->drawLine( 290, 510 - (30 * $i), 290, 540 - (30 * $i) )
        ->drawLine( 360, 510 - (30 * $i), 360, 540 - (30 * $i) )
        ->drawLine( 420, 510 - (30 * $i), 420, 540 - (30 * $i) )
        ->drawLine( 510, 510 - (30 * $i), 510, 540 - (30 * $i) )
        ->drawLine( 0, 510 - (30 * $i), 510, 510 - (30 * $i) );

      $pdf->pages[0]
        ->setFillColor( $cText )
        ->drawText( $item->getDescription(), $this->mmToPoints(13), 520 - (30 * $i) )
        ->drawText( $item->getQuantity(), 210, 520 - (30 * $i) )
        ->drawText( format_currency($item->getUnitPrice(), $currency), 240, 520 - (30 * $i), 'UTF-8' )
        ->drawText( format_currency($item->getPriceExVat(), $currency), 300, 520 - (30 * $i), 'UTF-8' )
        ->drawText( $item->getVat()->getPercentage() . '%', 370, 520 - (30 * $i) )
        ->drawText( format_currency($item->getPriceInclVat(), $currency), 450, 520 - (30 * $i), 'UTF-8' );
      $i++;
    }

    //Total
    $eindtotaal = $invoice->getTotal() + $invoice->getVat();
    $pdf->pages[0]
      ->drawLine( 340, 510 - (30 * $i), 510, 510 - (30 * $i) )
      ->drawText( 'Totaal (Excl)', 340, 520 - (30 * $i) )
      ->drawText( format_currency($invoice->getTotal(), $currency), 440, 520 - (30 * $i), 'UTF-8' ) // totaal zonder btw
      ->drawLine( 340, 480 - (30 * $i), 510, 480 - (30 * $i) )
      ->drawText( 'BTW', 340, 490 - (30 * $i) )
      ->drawText( format_currency($invoice->getVat(), $currency), 440, 490 - (30 * $i), 'UTF-8' ) //totaal BTW
      ->drawLine( 340, 430 - (30 * $i), 510, 430 - (30 * $i) )
      ->setFont( $font, 12 )
      ->drawText( 'Totaal (Incl)', 340, 440 - (30 * $i) )
      ->drawText( format_currency($eindtotaal, $currency), 440, 440 - (30 * $i), 'UTF-8' ); //Eindtotaal

    ///////////
    ////////// Footer:
    /////////
    $pdf->pages[0]
      ->setFont( $font, 10 )
      ->drawText( 'Opmerkingen', $this->mmToPoints(13), 180 )
      ->drawLine( 0, 170, 510, 170);

    $para = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ornare tristique sem nec porta. In rutrum nibh nec vehicula ornare. Duis quis mollis urna. Nunc a porta ipsum. Sed nec mi nec justo sollicitudin malesuada. Donec diam libero, mollis a mauris vel, posuere porttitor elit. Curabitur aliquet tincidunt accumsan. Donec volutpat a erat et ultricies. Donec a imperdiet metus. Nam euismod convallis tincidunt. Nulla sem turpis, pretium et aliquet ac, hendrerit in diam. ';

    $this->drawTextArea($pdf, $para, $this->mmToPoints(13), 150, 15, 110);

    return $pdf;
  }

///////////
////////// Tools:
/////////
  // Create wrapped text.
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

