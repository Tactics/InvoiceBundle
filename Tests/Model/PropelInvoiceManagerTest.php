<?php

namespace Tactics\InvoiceBundle\Tests\Model;

use Tactics\InvoiceBundle\Propel\InvoiceManager;

class InvoiceManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesAnInvoice()
    {
        $transformer = $this->getMock('Tactics\InvoiceBundle\Propel\InvoiceTransformer');
          
        $mgr = new InvoiceManager($transformer);
                
        $this->assertInstanceOf('Tactics\InvoiceBundle\Model\Invoice', $mgr->create(),          
            'Failed asserting that manager#create returns an Invoice object.'
        );
    }
}