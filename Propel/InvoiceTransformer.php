<?php

namespace Tactics\InvoiceBundle\Propel;

class InvoiceTransformer extends Transformer
{
    private $item_transformer;
        
    /**
     * Constructor
     * 
     * @param string $class
     * @param Transformer $item_transformer
     */
    public function __construct($class, Transformer $item_transformer)
    {
        $this->item_transformer = $item_transformer;
        
        parent::__construct($class);
    }
    
    /**
     * Geeft PropelInvoice terug op basis van $invoice
     * 
     * @param Tactics\InvoiceBundle\Model\Invoice $invoice
     * @return \PropelInvoice
     */
    public function toOrm($invoice)
    {
        $invoiceArray = $this->toArray($invoice);
        $customerArray = Helper::objectToClassAndId($invoice->getCustomer(), 'Customer');
        $propelInvoice = $this->ormObjectFromArray(array_merge($invoiceArray, $customerArray));
                        
        foreach ($invoice->getItems() as $item)
        {
            $propelInvoice->addPropelInvoiceItem($this->item_transformer->toOrm($item));
        }
        
        return $propelInvoice;
    }

    /**
     * Geeft domain invoice terug op basis van $propel_invoice
     * 
     * @param \PropelInvoice $propel_invoice
     * @return \Tactics\InvoiceBundle\Propel\Invoice
     */
    public function fromOrm($propel_invoice)
    {
        $invoice = parent::fromOrm($propel_invoice);
        
        $customer = Helper::classAndIdToObject($propel_invoice->toArray(), 'Customer');
        if ($customer)
        {
          $invoice->setCustomer($customer);
        }        
        
        foreach ($propel_invoice->getPropelInvoiceItems() as $propel_invoice_item)
        {
            $invoice->addItem($this->item_transformer->fromOrm($propel_invoice_item));
        }
        
        return $invoice;
    }
}
