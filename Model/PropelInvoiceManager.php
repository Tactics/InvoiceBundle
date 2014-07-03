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
     * @param int $id
     */
    public function delete(Invoice $invoice)
    {        
        $propelInvoice = $this->transformer->toOrm($invoice);
        $propelInvoice->delete();
    }
    
    
    /**
     * 
     * @param int $id
     * @return Tactics\InvoiceBundle\Model\Vat
     */
    public function findVat($id)
    {
        $propelVat = \PropelVatPeer::retrieveByPK($id);        
        
        return $this->transformer->vatFromOrm($propelVat);
    }
    
    public function saveVat(Vat $vat)
    {
        $propelVat = $this->transformer->vatToOrm($vat);
        $propelVat->setNew(!$vat->getId());
        $propelVat->save();
    }
    
    
    /**
     * Geeft alle Vats terug
     * 
     * @return array
     */
    public function getAllVats() 
    {
      $propelVats = \PropelVatPeer::doSelect(new \Criteria());
      $vats = array();
      foreach ($propelVats as $propelVat)
      {
        $vats[] = $this->transformer->vatFromOrm($propelVat);
      }
      
      return $vats;
    }
}
