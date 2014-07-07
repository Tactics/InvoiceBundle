<?php

namespace Tactics\InvoiceBundle\Model;

use Tactics\InvoiceBundle\Model\TransformableInterface;

interface TransformerInterface
{
    public function toOrm(TransformableInterface $domain_object);

    public function fromOrm($orm_object);
}
