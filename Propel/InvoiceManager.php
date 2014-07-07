<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model\ObjectManager;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\TransformableInterface;

class InvoiceManager extends ObjectManager
{   
    /**
     * 
     * @return Invoice
     */
    public function create()
    {
        return new Invoice();
    }
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\Invoice $invoice
     */
    public function save(TransformableInterface $invoice)
    {
        $propelInvoice = $this->transformer->toOrm($invoice);
        $propelInvoice->setNew(!$invoice->getId());
        $propelInvoice->save();
    }
    
    /**
     * 
     * @param int $id
     * @return Tactics\InvoiceBundle\Model\Invoice
     */
    public function find($id)
    {
        $propelInvoice = \PropelInvoicePeer::retrieveByPK($id);        
        
        return $this->transformer->fromOrm($propelInvoice);
    }
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\Invoice $invoice
     */
    public function delete(TransformableInterface $invoice)
    {        
        $propelInvoice = $this->transformer->toOrm($invoice);
        $propelInvoice->delete();
    }
}
