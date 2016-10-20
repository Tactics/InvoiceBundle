<?php

namespace Tactics\InvoiceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your config/container.yml files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @author Joris Hontelé <joris.hontele@tactics.be>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('invoice');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $this->addValidationSection($rootNode);

        return $treeBuilder;
    }
    
    private function addValidationSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('orm')
                  ->info('Configure the orm: propel|doctrine (todo)')                    
                  ->defaultValue('propel')
                ->end()
                ->scalarNode('accounting_software')
                  ->info('Configure the accounting software: ProAcc|Agresso')
                ->end()
                ->scalarNode('customer_class')
                  ->info('Configure the customer class')
                ->end()
                ->scalarNode('number_generator')
                  ->info('class that generates the invoice numbers')                    
                  ->defaultValue('Tactics\InvoiceBundle\Tools\NumberGenerator')
                ->end()
                ->scalarNode('journal_generator')
                  ->info('class that generates the journal numbers')                    
                  ->defaultValue('Tactics\InvoiceBundle\Tools\JournalGenerator')
                ->end()
                ->scalarNode('pdf_generator')
                  ->info('class that generates the invoice pdf')
                  ->defaultValue('Tactics\InvoiceBundle\Tools\PdfGenerator')
                ->end()
                ->scalarNode('options_generator')
                  ->info('class that can generate extra invoice options (i.e. payment_method)')
                  ->defaultValue('Tactics\InvoiceBundle\Tools\OptionsGenerator')
                ->end()
                ->scalarNode('invoice_converter')
                  ->info('class that converts the invoice for import in the accounting software')
                ->end()
            ->end()
        ;
    }
}
