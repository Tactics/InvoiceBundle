<?php

namespace Tactics\InvoiceBundle\Model;

class PropelInvoiceTransformer implements InvoiceTransformerInterface
{
    public function toOrm(Invoice $invoice)
    {
        $invoiceArray = $invoice->toArray();
        $customerArray = $this->objectToArray($invoice->getCustomer(), 'Customer');
        
        $propelInvoice = new \PropelInvoice();
        $propelInvoice->fromArray(array_merge($invoiceArray, $customerArray));
        
        foreach ($invoice->getItems() as $item)
        {
            $propelInvoiceItem = new \PropelInvoiceItem();
            $propelInvoiceItem->fromArray($item->toArray());
            
            $vat = $item->getVat();
            $propelInvoiceItem->setPropelVat($this->vatToOrm($vat));
            
            $propelInvoice->addPropelInvoiceItem($propelInvoiceItem);
        }
        
        return $propelInvoice;
    }

    public function fromOrm($propel_invoice)
    {
        $propelInvoiceArray = $propel_invoice->toArray();
        
        $invoice = new Invoice();
        $invoice->fromArray($propelInvoiceArray);
        
        $customer = $this->arrayToObject($propelInvoiceArray, 'Customer');
        $invoice->setCustomer($customer);
        
        foreach ($propel_invoice->getPropelInvoiceItems() as $propelInvoiceItem)
        {
            $item = new InvoiceItem();
            $item->fromArray($propelInvoiceItem->toArray());
            
            $propelVat = $propelInvoiceItem->getPropelVat();
            if ($propelVat)
            {
              $item->setVat($this->vatFromOrm($propelVat));
            }
            
            $invoice->addItem($item);            
        }
        
        return $invoice;
    }
    
    public function vatFromOrm($propel_vat)
    {
        $vat = new Vat();
        $vat->fromArray($propel_vat->toArray());
        
        return $vat;
    }
    
    public function vatToOrm(Vat $vat)
    {
        $propelVat = new \PropelVat();
        $propelVat->fromArray($vat->toArray());
        
        return $propelVat;
    }
    
    
    /**
     * Converts array with $className class and id to object
     * 
     * @param array $array
     * @param string $className
     * 
     * @return mixed The object
     */
    private function arrayToObject($array, $className)
    {
        $objectClass = isset($array[$className . 'Class']) ? $array[$className . 'Class'] : '';
        $objectId = isset($array[$className . 'Id']) ? $array[$className . 'Id'] : '';
        
        if (!($objectClass && $objectId) || !method_exists($objectClass . 'Peer', 'retrieveByPK'))
        {
           return;
        }

        return call_user_func($objectClass . 'Peer::retrieveByPK', $objectId);
    }
    
    /**
     * Converts object to array with object id and class
     * 
     * @param mixed $object
     *
     * @return array The object
     */
    private function objectToArray($object, $className)
    {
        return array(
          $className . 'Id' => $object->getId(),
          $className . 'Class' => get_class($object)
        );
    }
}
