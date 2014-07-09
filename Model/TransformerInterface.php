<?php

namespace Tactics\InvoiceBundle\Model;

interface TransformerInterface
{
    public function toOrm($domain_object);

    public function fromOrm($orm_object);
}
