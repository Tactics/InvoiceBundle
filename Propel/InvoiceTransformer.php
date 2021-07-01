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
        /* @var $propelInvoice \PropelInvoice */
        $propelInvoice = parent::toOrm($invoice);
        
        $propelInvoice->setCustomerClass(get_class($invoice->getCustomer()));
        $propelInvoice->setCustomerId($invoice->getCustomer()->getId());
                        
        $oldItems = \Misc::buildIndexedCache($propelInvoice->getPropelInvoiceItems());        
        foreach ($invoice->getItems() as $item)
        {
            if (isset($oldItems[$item->getId()]))
            {
                unset($oldItems[$item->getId()]);
            }
            $propelInvoice->addPropelInvoiceItem($this->item_transformer->toOrm($item));
        }
        
        // remove oldItems
        foreach ($oldItems as $oldItem)
        {
            $oldItem->delete();
        }
        
        return $propelInvoice;
    }

    /**
     * Geeft domain invoice terug op basis van $propel_invoice
     * 
     * @param \PropelInvoice $propel_invoice
     * @return \Tactics\InvoiceBundle\Propel\Invoice
     */
    public function fromOrm($propel_invoice, $force = false)
    {
        $invoice = parent::fromOrm($propel_invoice, $force);
        
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
