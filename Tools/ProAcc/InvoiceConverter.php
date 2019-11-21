<?php

namespace Tactics\InvoiceBundle\Tools\ProAcc;

use Facturatie\Customer\ProAccCustomer;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\InvoiceItem;
use Tactics\InvoiceBundle\Tools\CustomerFactoryInterface;
use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Tools\ConverterResult;

class InvoiceConverter
{
    private $customerFactory;
    private $journalMgr;

    /**
     * constructor
     *
     * @param ObjectManager $customerInfoMgr
     */
    public function __construct(CustomerFactoryInterface $customerFactory, ObjectManager $accountMgr, ObjectManager $vatMgr, ObjectManager $journalMgr)
    {
        $this->customerFactory = $customerFactory;
        $this->journalMgr = $journalMgr;
    }

    /**
     *
     * @param array[Invoice] $invoices
     * @param array $options
     * @return ConverterResult
     */
    public function convert($invoices, $options = array())
    {
        $data = array();
        foreach ($invoices as $invoice)
        {
            foreach ($this->getProAccVerkoopLijnen($invoice, $options) as $verkoopLijn)
            {
               $data[] = implode("\t", $verkoopLijn);
            }
        }
        $data[] = "99"; // add last line
        $result = (new ConverterResult())->add('verkopen.txt', 'text/csv', implode("\r\n", $data));

        // indien facturatie in proacc, aparte import in facturatiemodule
        if (isset($options['proacc_facturatie']) && $options['proacc_facturatie'])
        {
            $data2 = [];
            foreach ($invoices as $invoice)
            {
                foreach ($this->getProAccFacturatieLijnen($invoice, $options) as $facturatieLijn)
                {
                    $data2[] = implode("\t", $facturatieLijn);
                }
            }
            $data2[] = "99"; // add last line

            $result->add('facturen.txt', 'text/csv', implode("\r\n", $data2));
        }

        return $result;
    }

    /**
     *
     * @param Invoice $invoice
     * @param array $options
     * @return array
     */
    private function getProAccVerkoopLijnen(Invoice $invoice, $options)
    {
        $blancos = $this->getBlancos($options);
        $omschrijving = $this->getOmschrijving($invoice);
        $boekingsPeriode = $this->getBoekingsperiode($invoice);
        $withVat = $invoice->withVat();
        $isCreditNote = $invoice->isCreditNote();
        $vat = abs($invoice->getVat());
        $total = abs($invoice->getTotal());

        $lines = array();
        $first = true;
        foreach ($invoice->getItems() as $cnt => $item)
        {
            if ($item->getType() == 'text') continue;

            $line = array_merge($blancos, array(
              'A' => $first ? ($isCreditNote ? '2' : '1') : '3',
              'B' => $this->getKlantcode($invoice),
              'C' => $invoice->getJournalCode(),
              'D' => $invoice->getNumber(),
              'E' => $invoice->getDate('d/m/Y'),
              'F' => $boekingsPeriode,
              'G' => '',
              'H' => $invoice->getDateDue('d/m/Y'),
              'I' => 'EUR',
              'J' => 1,
              'K' => number_format($total + $vat, 2, ',', ''),
              'L' => number_format($total + $vat, 2, ',', ''),
              'M' => number_format($total, 2, ',', ''),
              'N' => $withVat ? number_format($vat, 2, ',', '') : 0,
              'O' => 0,
              'X' => $withVat ? number_format($this->getMvh($invoice, '21'), 2, ',', '') : 0, // maatstaf heffing 21% BTW hele dossier
              'Z' => $omschrijving,
              'AA' => $item->getGlAccountCode(),
              'AB' => $item->getAnalytical1AccountCode() ?: '',
              'AC' => number_format(abs($item->getPriceExVat()), 2, ',', ''),
              'AD' => $item->getVatPercentage() ? number_format(abs($item->getPriceExVat()), 2, ',', '') : 0, // idem als AC - fin.korting, maar fin.korting wordt niet gebruikt
              'AE' => $withVat ? number_format($item->getVatPercentage(), 2, ',', '') : 0,
              'AG' => substr($item->getDescription(), 0, 50), // omschrijving, voor inovant moet hier de opleidingscode inkomen
              'AI' => $item->getAnalytical2AccountCode() ?: '',
              'AK' => '',
              'AL' => $invoice->getDatePaid() ? '1' : '0',
              'AM' => ''
            ));

            if (isset($options['inovant']) && $options['inovant'])
            {
              $line['AO'] = $invoice->getRef();
            }

            $lines[] = $line;

            $first = false;
        }

        return $lines;
    }

    /**
     *
     * @param array $options
     * @return type
     */
    private function getBlancos($options)
    {
        $rangeAA = array_map(create_function('$object', 'return "A{$object}";'), range('A', isset($options['inovant']) && $options['inovant'] ? 'O' : 'M'));
        $range = array_merge(range('A', 'Z'), $rangeAA);
        return array_combine($range, array_fill(0, count($range), '0'));
    }

    private function getOmschrijving(Invoice $invoice)
    {
        $omschrijving = $invoice->getRef() ?: ($invoice->getItems() ? $invoice->getItems()[0]->getGroupDescription() : '');
        return substr($omschrijving, 0, 20);
    }

    /**
     * Geeft de klantcode terug
     *
     * @return string
     * @todo: proacc_number ophalen/genereren van nieuwe
     */
    private function getKlantcode(Invoice $invoice)
    {
        $customer = $this->customerFactory->getCustomer($invoice);

        return $customer->getExternalId($invoice->getSchemeId());
    }

    /**
     *
     * @param Invoice $invoice
     * @return string
     *
     * @todo: fix dependency on \Config::BOEKINGSPERIODE
     */
    protected function getBoekingsperiode(Invoice $invoice)
    {
        // facturen en creditnota's met BTW afh van config val
        if ($invoice->withVat())
        {
            $ns = \sfContext::getInstance()->getUser()->getBedrijf()->getVarNaam();
            return \ConfigPeer::get(\Config::BOEKINGSPERIODE, '', $ns);
        }

        return $invoice->getDate('ym');
    }

    /**
     * @param Invoice $invoice
     * @param $options
     */
    private function getProAccFacturatieLijnen(Invoice $invoice, $options)
    {
        $blancos = $this->getBlancos($options);
        $first = true;
        $isCreditNote = $invoice->isCreditNote();
        /** @var ProAccCustomer $customer */
        $customer = $this->customerFactory->getCustomer($invoice);
        $withVat = $invoice->withVat();

        foreach ($invoice->getItems() as $cnt => $item)
        {
            if ($item->getType() == 'text') continue;

            $line = array(
                'A' => $first ? '1' : '3',
                'B' => $isCreditNote ? 'C' : 'F',
                'C' => $isCreditNote ? 'CRED' : 'FACT',
                'D' => $invoice->getNumber(),
                'E' => $this->getKlantcode($invoice),
                'F' => $invoice->getDate('d/m/Y'),
                'G' => $invoice->getRef(),
                'H' => $customer->getRef($invoice),
                'I' => 'EUR',
                'J' => 1,
                'K' => $customer->getBtwStatus(),
                'L' => '', // vertegenwoordiger?
                'M' => 30, // code betalingswijze
                'N' => $invoice->getDateDue('d/m/Y'),
                'O' => '', // globale korting %
                'P' => '', // fin. korting %
                'Q' => '', // kredietbeperking korting %
                'R' => '', // artikelcode of *1, *2 of M + => omschrijving in volgend veld
                'S' => $item->getDescription(), // max 300
                'T' => $item->getQuantity(),
                'U' => number_format($item->getUnitPrice(), 2, ',', ''),
                'V' => $withVat ? number_format($item->getVatPercentage(), 2, ',', '') : 0,
                'W' => '', // lijnkorting%
                'X' => $item->getGlAccountCode(), // Algemene rekening
                'Y' => $item->getAnalytical1AccountCode(), // analytiche code
                'Z' => '', // eenheid?
                'AA' => '', // commissie vertegenwoordiger %
                'AB' => '', // levertijd ?
                'AC' => '', // leveringsvoorwaarden
                'AD' => '', // voorschot
                'AE' => substr($customer->getNaam(), 0, 30), // max 30 , naam 1
                'AF' => '', // max 30 , naam 2
                'AG' => '', // max 30 , naam 3
                'AH' => substr($customer->getStraatNummerBus(), 0, 30),
                'AI' => $customer->getPostcode(),
                'AJ' => $customer->getGemeente(),
                'AK' => $customer->getLandcode(),
                'AL' => $customer->getLandnaam(),
                'AM' => 1, // prijzen btw in
                'AN' => 0,  // document geprint
                'AO' => $item->getAnalytical2AccountCode() // actienummer
            );

            $lines[] = $line;
            $first = false;
        }

        return $lines;
    }

  /**
   * @param Invoice $invoice
   * @param string $percentage
   * @return float
   */
  private function getMvh(Invoice $invoice, $percentage)
  {
    return array_reduce($invoice->getItems(), function($carry, InvoiceItem $item) use ($percentage) {
      return $item->getVatPercentage() === $percentage ? bcadd($carry, abs($item->getPriceExVat()), 2) : $carry;
    }, 0);
  }
}

