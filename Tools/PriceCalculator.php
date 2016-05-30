<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\InvoiceItem;

class PriceCalculator
{
    public function calculate(InvoiceItem $item)
    {
        $item->setPriceExVat(bcmul($item->getQuantity(), $item->getUnitPrice(), 2));
        $vatMultiplier = bcdiv($item->getVatPercentage(), 100, 2);
        $item->setVat(round(bcmul($item->getPriceExVat(), $vatMultiplier, 3), 2));
        $item->setPriceInclVat(bcadd($item->getPriceExVat(), $item->getVat(), 2));
    }
}