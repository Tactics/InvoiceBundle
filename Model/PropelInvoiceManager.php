<?php

namespace Tactics\InvoiceBundle\Model;

class PropelInvoiceManager extends InvoiceManager
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
    public function save(Invoice $invoice)
    {
        $propelInvoice = $this->transformer->toOrm($invoice);        
        $propelInvoice->save();
    }
    
    /**
     * 
     * @param int $id
     * @return \Invoice
     */
    public function find($id)
    {
        $propelInvoice = \PropelInvoicePeer::retrieveByPK($id);        
        
        return $this->transformer->fromOrm($propelInvoice);
    }
    
    /**
     * 
     * @param int $id
     */
    public function delete(Invoice $invoice)
    {        
        $propelInvoice = $this->transformer->toOrm($invoice);
        $propelInvoice->delete();
    }
}
