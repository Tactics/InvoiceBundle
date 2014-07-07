<?php

namespace Tactics\InvoiceBundle\Model;

interface TransformableInterface
{
  public function fromArray($arr);
  
  public function toArray();
}
