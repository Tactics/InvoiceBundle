<?php

namespace Tactics\InvoiceBundle\Model;

class PropelInvoiceTransformer implements InvoiceTransformerInterface
{
    public function toOrm(Invoice $invoice)
    {
        $propelInvoice = new \PropelInvoice();
        $propelInvoice->fromArray($invoice->toArray());
        
        foreach ($invoice->getItems() as $item)
        {
            $propelInvoiceItem = new \PropelInvoiceItem();
            $propelInvoiceItem->fromArray($item->toArray());
            $propelInvoice->addPropelInvoiceItem($propelInvoiceItem);
        }
        
        return $propelInvoice;
    }

    public function fromOrm($propel_invoice)
    {
        $propelInvoiceArray = $propel_invoice->toArray();
        $this->convertCustomerIdAndClassToObject($propelInvoiceArray);
        
        $invoice = new Invoice();
        $invoice->fromArray($propelInvoiceArray);
        
        foreach ($propel_invoice->getPropelInvoiceItems() as $propelInvoiceItem)
        {
            $item = new InvoiceItem();
            $item->fromArray($propelInvoiceItem->toArray());
            $invoice->addItem($item);            
        }
        
        return $invoice;
    }
    
    /**
     * Converts CustomerId and CustomerClass to Customer object
     * 
     * @param array $propel_invoice
     *
     * @return mixed The object
     */
    private function convertCustomerIdAndClassToObject(&$propel_invoice)
    {
      $customerClass = $propel_invoice['CustomerClass'];
      $customerId = $propel_invoice['CustomerId'];
      
      unset($propel_invoice['CustomerClass']);
      unset($propel_invoice['CustomerId']);
      
      if (!($customerClass && $customerId) || !method_exists($customerClass . 'Peer', 'retrieveByPK'))
      {
         return;
      }

      $propel_invoice['Customer'] = call_user_func($customerClass . 'Peer::retrieveByPK', $customerId);
    }
}
