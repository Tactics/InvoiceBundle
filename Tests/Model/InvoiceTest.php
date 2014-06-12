<?php

namespace Tactics\InvoiceBundle\Tests\Model;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\InvoiceItem;

class InvoiceTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->invoice = new Invoice();
        
        $this->item1 = new InvoiceItem();
        $this->item1->setQuantity(3);
        $this->item1->setUnitPrice(3.75);
        
        $this->item2 = new InvoiceItem();
        $this->item2->setQuantity(7);
        $this->item2->setUnitPrice(2.21);
        
        $this->items = array($this->item1, $this->item2);
    }
    
    public function testTotalIsZeroByDefault()
    {
        $this->assertSame(0, $this->invoice->getTotal());
    }

    public function testAddItems()
    {
        foreach ($this->items as $item)
        {
            $this->invoice->addItem($item);
        }
        
        $this->assertCount(count($this->items), $this->invoice->getItems());
        $this->assertSame($this->items, $this->invoice->getItems());
    }
    
    /**
     * @depends testAddItems
     * @depends testTotalIsZeroByDefault
     */
    public function testCalculatesTotalOnItemsAdded()
    {
        $total1 = $this->item1->getQuantity() * $this->item1->getUnitPrice();
        $total2 = $this->item2->getQuantity() * $this->item2->getUnitPrice();
        
        $this->invoice->addItem($this->item1);        
        $this->assertSame($total1, $this->invoice->getTotal());
        
        $this->invoice->addItem($this->item2);        
        $this->assertSame($total1 + $total2, $this->invoice->getTotal());
    }
}