<?php

namespace Tactics\InvoiceBundle\Propel;

class InvoiceItemTransformer extends Transformer
{
    private $vat_transformer;    
        
    /**
     * Constructor
     * 
     * @param string $class
     * @param Transformer $vat_transformer
     */
    public function __construct($class, Transformer $vat_transformer)
    {
        $this->vat_transformer = $vat_transformer;        
        
        parent::__construct($class);
    }
    
    /**
     * Geeft PropelInvoiceItem terug op basis van $invoice_item
     * 
     * @param Tactics\InvoiceBundle\Model\InvoiceItem $invoice_item
     * @return \PropelInvoiceItem
     */
    public function toOrm($invoice_item)
    {
        $propelInvoiceItem = parent::toOrm($invoice_item);
        
        if ($invoice_item->getVat())
        {
            $vat = $invoice_item->getVat();
            $propelVat = $this->vat_transformer->toOrm($vat);
            $propelInvoiceItem->setPropelVat($propelVat); 
        }            

        return $propelInvoiceItem;
    }
    
    /**
     * Geeft domain invoice_item terug op basis van $propel_invoice_item
     * 
     * @param \PropelInvoice $propel_invoice_item
     * @return \Tactics\InvoiceBundle\Propel\InvoiceItem
     */
    public function fromOrm($propel_invoice_item)
    {
        $item = parent::fromOrm($propel_invoice_item);
        
        if ($propel_invoice_item->getPropelVat())
        {
            $propelVat = $propel_invoice_item->getPropelVat();
            $vat = $this->vat_transformer->fromOrm($propelVat);
            $item->setVat($vat);
        }           
        
        return $item;
    }      
}

