<?php

namespace Tactics\InvoiceBundle\Tools\Agresso;

use Tactics\InvoiceBundle\Tools\CustomerFactoryInterface;
use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\InvoiceItem;
use Tactics\InvoiceBundle\Tools\ConverterResult;

class InvoiceConverter
{
    private $customerFactory;
    private $accountMgr;
    private $vatMgr;
    
    /**
     * constructor
     *
     * @param ObjectManager $customerSchemeMgr
     */
    public function __construct(CustomerFactoryInterface $customerFactory, ObjectManager $accountMgr, ObjectManager $vatMgr, ObjectManager $journalMgr)
    {
        $this->customerFactory = $customerFactory;
        $this->accountMgr = $accountMgr;
        $this->vatMgr = $vatMgr;
    }
    
    /**
     * 
     * @param array[Invoice] $invoices
     */
    public function convert($invoices)
    {
        $now = new \myDate(time());
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= sprintf('<!--XML generated by Tactics on %s-->', $now->format('f'));
        $xml .= '<!--Link Agresso TAC_BRUGGE-->';
        $xml .= sprintf('<Facturen xmlns="Tactics/Facturen/%u/%02u">', $now->format('Y'), $now->format('MM'));
        $xml .= sprintf('<BatchId>%s</BatchId>', 'TAC' . $now->format('yyyyMMddHms'));
        $xml .= '<ReportClient>SB</ReportClient>'; // Stad Brugge
        
        foreach ($invoices as $invoice)
        {
            $xml .= $this->getVoucher($invoice);
        }
        $xml .= '</Facturen>';
        
        return new ConverterResult('verkopen.xml', 'text/xml', $xml);
    }
    
    private function getVoucher(Invoice $invoice)
    {
        $voucher = sprintf('<VoucherNo>%u</VoucherNo>', $invoice->getNumber()); // factuurnr
        $voucher .= sprintf('<VoucherType>%s</VoucherType>', $invoice->getJournalCode()); // dagboek/journaal
        $voucher .= '<CompanyCode>SB</CompanyCode>'; // Stad Brugge
        $voucher .= sprintf('<Period>%s</Period>', $invoice->getDate('Ym')); // boekingsperiode, nog afh van instelbare periode?
        $voucher .= sprintf('<VoucherDate>%s</VoucherDate>', $invoice->getDate()); // factuurdatum
        
        // 1 AR transactie (klantrekening) per factuur
        $voucher .= $this->getArTransaction($invoice); // klantrekening
        
        // 1 GL en 1 TX transactie (indien met BTW) per Lijn
        foreach ($invoice->getItems() as $item)
        {
            if ($item->getType() !== 'invoice') continue;  // we exporteren enkel invoice lijnen
            $voucher .= $this->getGlTransaction($item); // verkooprekening
            if ($item->getVatPercentage() > 0)
            {
                $voucher .= $this->getTxTransaction($item); // alleen indien met BTW
            }            
        }
        
        return sprintf('<Voucher>%s</Voucher>', $voucher);
    }
    
    /**
     * transactie voor klantrekening
     * 
     * @param Invoice $invoice
     * @return string
     * @todo: GL account indien waarborg???
     */
    private function getArTransaction(Invoice $invoice)
    {
        $arTransaction = '<Transaction>';
        $arTransaction .= '<TransType>AR</TransType>';
        $arTransaction .= sprintf('<Description>%s</Description>', $invoice->getRef()); // globale omschrijving?
        $arTransaction .= sprintf('<TransDate>%s</TransDate>', $invoice->getDate()); // factuurdatum;
        
        // AR amount is positief indien factuur, negatief indien creditnota!
        $amount = bcadd($invoice->getTotal(), $invoice->getVat(), 2);
        $arTransaction .= sprintf('<Amounts><Amount>%.2f</Amount></Amounts>', $amount); // bedrag inclusief btw
        
        $arTransaction .= '<GLAnalysis>';
        $account = '40000000'; // altijd 40000000, tenzij waarborgen: dan 41600000
        $arTransaction .= sprintf('<Account>%s</Account>', $account);
        $arTransaction .= '</GLAnalysis>';
        
        $arTransaction .= $this->getApArInfo($invoice);
        
        $arTransaction .= '</Transaction>';
        
        return $arTransaction;
    }
    
    /**
     * transactie voor verkooprekening
     * 
     * @param InvoiceItem $item
     * @return string
     */
    private function getGlTransaction(InvoiceItem $item)
    {
        /* @var $invoice Invoice */
        $invoice = $item->getInvoice();
        
        $glTransaction = '<Transaction>';
        $glTransaction .= '<TransType>GL</TransType>';       
        $glTransaction .= sprintf('<Description>%s</Description>', $item->getDescription()); // globale omschrijving?
        $glTransaction .= sprintf('<TransDate>%s</TransDate>', $invoice->getDate()); // factuurdatum;
        
        // amount is negatief indien factuur, positief indien creditnota!
        $amount = bcsub(0, bcmul($item->getQuantity(), $item->getUnitPrice(), 2), 2);
        $glTransaction .= sprintf('<Amounts><Amount>%.2f</Amount></Amounts>', $amount); // bedrag exclusief btw
        
        $glTransaction .= '<GLAnalysis>';
        $glTransaction .= sprintf('<Account>%s</Account>', $item->getGlAccountCode()); // grootboekrekening = gl_account_code
        $glTransaction .= sprintf('<Dim1>%s</Dim1>', $item->getAnalytical1AccountCode());
        $glTransaction .= sprintf('<Dim2>%s</Dim2>', $item->getAnalytical2AccountCode());
        $glTransaction .= sprintf('<Dim3>%s</Dim3>', $item->getAnalytical3AccountCode());
        $glTransaction .= sprintf('<Dim4>%s</Dim4>', $item->getAnalytical4AccountCode());
        $glTransaction .= sprintf('<Dim5>%s</Dim5>', $item->getAnalytical5AccountCode());
        $glTransaction .= sprintf('<Dim6>%s</Dim6>', $item->getAnalytical6AccountCode());
        $glTransaction .= sprintf('<Dim7>%s</Dim7>', $item->getAnalytical7AccountCode());
        
        // vat_code of contra_account_code en btw_account_code nog opslaan bij item?
        $vat = $this->vatMgr->searchOne(array(
            'percentage' => $item->getVatPercentage(), 'scheme_id' => $invoice->getSchemeId()
        )); 
        $glTransaction .= sprintf('<TaxCode>%s</TaxCode>', $vat->getCode());; // BTW codes nog aan te leveren
        $glTransaction .= '</GLAnalysis>';
        
        $glTransaction .= $this->getApArInfo($invoice);
        
        $glTransaction .= '</Transaction>';
        
        return $glTransaction;
    }
    
    /**
     * transactie voor btw rekening
     * 
     * @param InvoiceItem $item
     * @return string
     */
    private function getTxTransaction(InvoiceItem $item)
    {
        /* @var $invoice Invoice */
        $invoice = $item->getInvoice();
        
        $txTransaction = '<Transaction>';
        $txTransaction .= '<TransType>TX</TransType>';       
        
        // amount is negatief indien factuur, positief indien creditnota!
        $baseAmount = bcsub(0, bcmul($item->getQuantity(), $item->getUnitPrice(), 2), 2);
        $amount = bcdiv(bcmul($baseAmount, $item->getVatPercentage(), 2), 100, 2);
        $txTransaction .= sprintf('<Amounts><Amount>%.2f</Amount></Amounts>', $amount); // btw
                
        $txTransaction .= '<GLAnalysis>';
        
        // vat_code of contra_account_code en btw_account_code nog opslaan bij item?
        $vat = $this->vatMgr->searchOne(array(
            'percentage' => $item->getVatPercentage(), 'scheme_id' => $invoice->getSchemeId()
        )); 
        $txTransaction .= sprintf('<Account>%s</Account>', $vat->getAccountCode()); // vat.account_code
        $txTransaction .= sprintf('<TaxCode>%s</TaxCode>', $vat->getCode());; // BTW codes nog aan te leveren
        $txTransaction .= '</GLAnalysis>';
        
        $txTransaction .= '<TaxTransInfo>';
        $txTransaction .= sprintf('<Account2>%s</Account2>', $item->getGlAccountCode()); // verwijst naar overeenkomende account van gl transactie
        $txTransaction .= sprintf('<BaseAmount>%.2f</BaseAmount>', $baseAmount); // heffingsbedrag btw
        $txTransaction .= sprintf('<BaseCurr>%.2f</BaseCurr>', $baseAmount); // altijd identiek?
        $txTransaction .= '</TaxTransInfo>';
        
        $txTransaction .= $this->getApArInfo($item->getInvoice());
        
        $txTransaction .= '</Transaction>';
        
        return $txTransaction;
    }
    
    private function getApArInfo(Invoice $invoice)
    {
        $customer = $this->customerFactory->getCustomer($invoice);
        
        $apArInfo = '<ApArInfo>';
        $apArInfo .= sprintf('<ApArGroup>%s</ApArGroup>', $customer->getApArGroup());
        $apArInfo .= sprintf('<ApArNo>%s</ApArNo>', $customer->getApArNo($invoice->getSchemeId()));
        $apArInfo .= sprintf('<InvoiceNo>%u</InvoiceNo>', $invoice->getNumber()); // factuurnr
        $apArInfo .= sprintf('<Duedate>%s</Duedate>', $invoice->getDateDue()); // due date
        $apArInfo .= sprintf('<BacsId>%s</BacsId>', $invoice->getStructuredCommunication()); // gestructureerde mededeling
        // nog aan te leveren, voor onderscheid overschrijving/online betaling
        $apArInfo .= '<PayMethod></PayMethod>';
        $apArInfo .= '</ApArInfo>';
        
        return $apArInfo;
    }
}
