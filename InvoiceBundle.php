<?php

namespace Tactics\InvoiceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Description of InvoiceBundle
 *
 * @author Joris Hontelé <joris.hontele@tactics.be>
 */
class InvoiceBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}


