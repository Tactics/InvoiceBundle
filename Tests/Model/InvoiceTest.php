<?php

namespace Tactics\InvoiceBundle\Tests\Model;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\InvoiceItem;

class InvoiceTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->invoice = new Invoice();
        
        $item1 = new InvoiceItem();
        $item1->setQuantity(3);
        $item1->setUnitPrice(3.75);
        
        $item2 = new InvoiceItem();
        $item2->setQuantity(7);
        $item2->setUnitPrice(2.21);
        
        $this->items = array($item1, $item2);
    }
    
    public function testSetDateThrowsAnExceptionOnIllegalInput()
    {
        
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
    public function testRecalculatesTotalOnItemsAdded()
    {
        foreach ($this->items as $item)
        {
            $this->invoice->addItem($item);
        }
             
        $this->assertSame(26.72, $invoice->getTotal());
    }
}