<?php

namespace Tactics\InvoiceBundle\Tests\Model;

use Tactics\InvoiceBundle\Model\PropelInvoiceManager;
use Tactics\InvoiceBundle\Model\Invoice;

class PropelInvoiceManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesAnInvoice()
    {
        $transformer = $this->getMock('Tactics\InvoiceBundle\Model\InvoiceTransformerInterface');
          
        $mgr = new PropelInvoiceManager($transformer);
                
        $this->assertInstanceOf('Tactics\InvoiceBundle\Model\Invoice', $mgr->create(),          
            'Failed asserting that manager#create returns an Invoice object.'
        );
    }
}