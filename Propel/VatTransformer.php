<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model\TransformerInterface;
use Tactics\InvoiceBundle\Model\Vat;
use Tactics\InvoiceBundle\Model\TransformableInterface;

class VatTransformer implements TransformerInterface
{
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\TransformableInterface $vat
     * @return \PropelVat
     */
    public function toOrm(TransformableInterface $vat)
    {
        $propelVat = new \PropelVat();
        $propelVat->fromArray($vat->toArray());
        
        return $propelVat;
    }
    
    /**
     * 
     * @param \PropelVat $propel_vat
     * @return \Tactics\InvoiceBundle\Model\Vat
     */
    public function fromOrm($propel_vat)
    {
        $vat = new Vat();
        $vat->fromArray($propel_vat->toArray());
        
        return $vat;
    }
}
