<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\InvoiceItem;

class InvoiceItemTransformer extends Transformer
{
    /**
     * Geeft PropelInvoiceItem terug op basis van $invoice
     * 
     * @param InvoiceItem $item
     * @return \PropelInvoiceItem
     */
    public function toOrm($item)
    {
        /** @var \PropelInvoiceItem $propelInvoiceItem */
        $propelInvoiceItem = parent::toOrm($item);

        if ($object = $item->getObject())
        {
            $propelInvoiceItem->setObjectClass(get_class($object));
            $propelInvoiceItem->setObjectId($object->getId());
        }

        return $propelInvoiceItem;
    }

    /**
     * Geeft domain invoice_item terug op basis van $propelInvoiceItem
     * 
     * @param \PropelInvoiceItem $propelInvoiceItem
     * @return \Tactics\InvoiceBundle\Propel\InvoiceItem
     */
    public function fromOrm($propelInvoiceItem)
    {
        $invoiceItem = parent::fromOrm($propelInvoiceItem);
        
        $object = Helper::classAndIdToObject($propelInvoiceItem->toArray(), 'Object');
        if ($object)
        {
            $invoiceItem->setObject($object);
        }
        
        return $invoiceItem;
    }
}
