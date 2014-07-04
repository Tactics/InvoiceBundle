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
  public static function generatePdf(Invoice $invoice)
  {
    $pdf = new PdfDocument();
    $pdf->pages[0] = new Page( Page::SIZE_A4 ); // print marge van 10/13 mm Max dimensions A4 = 210mm x 297mm / 595.28 Pts 841.89 Pts

    //standaard gebruikt font.
    $font = Font::fontWithName(Font::FONT_HELVETICA);

    //logo
    $logo = Image::imageWithPath('images/logo-provant.png');

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
      ->drawImage( $logo, self::mmToPoints(13), 750, 189, 802);

    // Factuur + Factuur datum
    $pdf->pages[0]
      ->setFont( $font , 12 )
      ->setFillColor($cLightText)
      ->drawText( 'Factuur ' . $invoice->getNumber(), self::mmToPoints(13), 730 );
    $pdf->pages[0]
      ->setFont( $font, 10)
      ->drawText( $invoice->getDate(), self::mmToPoints(13), 715 );

    // Onze gegevens
    $pdf->pages[0]
      ->setFillColor($cText)
      ->setFont( $font , 14 )
      ->drawText( 'Inovant', 300, 780 );
    $pdf->pages[0]
      ->setFont( $font , 10 )
      ->drawText( 'Kerkstraat 115', 300, 755 )
      ->drawText( '2940 Hoevenen', 300, 740 )
      ->drawText ( 'BTW ############' , 300, 725);

    //Ontvanger
    $pdf->pages[0]
      ->setFont( $font , 14 )
      ->drawText( 'Benjamin Boutmans', 300, 665 );
    $pdf->pages[0]
      ->setFont( $font , 10 )
      ->drawText( 'Lange Sterrestraat 53 Bus 11', 300, 640 )
      ->drawText( '2180 Ekeren', 300, 625 )
      ->drawText ( '################' , 300, 610);

    ///////////
    ////////// Items:
    /////////
    //Items Header
    $pdf->pages[0]
      ->setLineColor( $cDraw )
      ->setFillColor( $cDraw )
      ->drawRectangle( 0, 530, 510, 570 );
    $pdf->pages[0]
      ->setFont( $font, 10 )
      ->setFillColor( $cWhite )
      ->setLineColor( $cLightText )
      ->setLineWidth( 0.5 )
      ->drawText( 'Omschrijving ' , self::mmToPoints(13) , 546 )
      ->drawLine( 200, 530, 200, 570 )
      ->drawText( '#', 210, 546 )
      ->drawLine( 230, 530, 230, 570 )
      ->drawText( 'Prijs/stuk' , 240, 546 )
      ->drawLine( 290 , 530, 290, 570 )
      ->drawText( 'Prijs (Excl)' , 300, 546 )
      ->drawLine( 360, 530, 360, 570 )
      ->drawText( 'BTW (%)', 370, 546 )
      ->drawLine( 420, 530, 420, 570 )
      ->drawText( 'Prijs (Incl)', 450, 546 );

    //Items
    $i = 0;
    $items = $invoice->getItems();
    foreach($items as $item)
    {
      $pdf->pages[0]
        ->drawLine( 200, 500 - (30 * $i), 200, 530 - (30 * $i) )
        ->drawLine( 230, 500 - (30 * $i), 230, 530 - (30 * $i) )
        ->drawLine( 290, 500 - (30 * $i), 290, 530 - (30 * $i) )
        ->drawLine( 360, 500 - (30 * $i), 360, 530 - (30 * $i) )
        ->drawLine( 420, 500 - (30 * $i), 420, 530 - (30 * $i) )
        ->drawLine( 510, 500 - (30 * $i), 510, 530 - (30 * $i) )
        ->drawLine( 0, 500 - (30 * $i), 510, 500 - (30 * $i) );

      $pdf->pages[0]
        ->setFillColor( $cText )
        ->drawText( $item->getDescription(), self::mmToPoints(13), 510 - (30 * $i) )
        ->drawText( $item->getQuantity(), 210, 510 - (30 * $i) )
        ->drawText( $item->getUnitPrice(), 240, 510 - (30 * $i) )
        ->drawText( $item->getPriceExVat(), 300, 510 - (30 * $i) )
        ->drawText( $item->getVat() . '%', 370, 510 - (30 * $i) )
        ->drawText( $item->getPriceInclVat(), 450, 510 - (30 * $i) );
      $i++;
    }

    //Total
    $pdf->pages[0]
      ->drawLine( 340, 500 - (30 * $i), 510, 500 - (30 * $i) )
      ->drawText( 'Totaal (Excl)', 340, 510 - (30 * $i) )
      ->drawText( $invoice->getTotal(), 430, 510 - (30 * $i))
      ->drawLine( 340, 470 - (30 * $i), 510, 470 - (30 * $i) )
      ->drawText( 'BTW', 340, 480 - (30 * $i) )
      ->drawText( $invoice->getVat(), 430, 480 - (30 * $i))
      ->drawLine( 340, 420 - (30 * $i), 510, 420 - (30 * $i) )
      ->setFont( $font, 12 )
      ->drawText( 'Totaal (Incl)', 340, 430 - (30 * $i) )
      ->drawText( $invoice->getTotal(), 430, 430 - (30 * $i));

    ///////////
    ////////// Footer:
    /////////
    $pdf->pages[0]
      ->setFont( $font, 10 )
      ->drawText( 'Opmerkingen', self::mmToPoints(13), 180 )
      ->drawLine( 0, 170, 510, 170);

    $para = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ornare tristique sem nec porta. In rutrum nibh nec vehicula ornare. Duis quis mollis urna. Nunc a porta ipsum. Sed nec mi nec justo sollicitudin malesuada. Donec diam libero, mollis a mauris vel, posuere porttitor elit. Curabitur aliquet tincidunt accumsan. Donec volutpat a erat et ultricies. Donec a imperdiet metus. Nam euismod convallis tincidunt. Nulla sem turpis, pretium et aliquet ac, hendrerit in diam. ';

    self::drawTextArea($pdf, $para, self::mmToPoints(13), 150, 15, 110);

    return $pdf;
  }

///////////
////////// Tools:
/////////
  // Create wrapped text.
  private static function drawTextArea($pdf, $text, $pos_x, $pos_y, $height, $length = 0, $offset_x = 0, $offset_y = 0)
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
  private static function mmToPoints( $mm )
  {
    return $mm / 25.4 * 72;
  }
}

