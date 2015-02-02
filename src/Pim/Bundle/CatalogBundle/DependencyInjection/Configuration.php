<?php

namespace Pim\Bundle\CatalogBundle\DependencyInjection;

use Akeneo\Component\StorageUtils\Storage;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Pim Catalog bundle configuration
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pim_catalog');

        $rootNode
            ->children()
                ->scalarNode('product_storage_driver')
                    ->defaultValue(Storage::DRIVER_DOCTRINE_ORM)
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
