<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\InvoiceItem;

class PriceCalculator implements PriceCalculatorInterface
{
    public function calculate(InvoiceItem $item)
    {
        $item->setPriceExVat(bcmul($item->getQuantity(), $item->getUnitPrice(), 2));
        $vatMultiplier = bcdiv(bcadd(100, $item->getVatPercentage(), 2), 100, 2);
        $item->setPriceInclVat(bcmul($item->getPriceExVat(), $vatMultiplier, 2));
        $item->setVat(bcsub($item->getPriceInclVat(), $item->getPriceExVat(), 2));
    }
}