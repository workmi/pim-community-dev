<?php

namespace Akeneo\Bundle\StorageUtilsBundle\DependencyInjection;

use Akeneo\Component\StorageUtils\Storage;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AkeneoStorageUtilsExtension extends Extension
{
    // TODO: to trash
    /** @staticvar string */
    const DOCTRINE_ORM = 'doctrine/orm';

    // TODO: to trash
    /** @staticvar string */
    const DOCTRINE_MONGODB_ODM = 'doctrine/mongodb-odm';

    // TODO: to trash
    /** @var string */
    protected static $storageDriver;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->registerStorages($config['storages'], $container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('doctrine.yml');
        $loader->load('factories.yml');
    }

    /**
     * TODO: to trash
     *
     * @return string
     */
    public static function getStorageDriver()
    {
        return self::$storageDriver;
    }

    /**
     * Register the storages parameters in the container
     *
     * @param array            $storages
     * @param ContainerBuilder $container
     */
    private function registerStorages(array $storages, ContainerBuilder $container)
    {
        $storageParamPattern = $this->getAlias() . '.storage_driver.%s';
        $driverParamPattern = $this->getAlias() . '.storage_driver.%s.%s';

        foreach ($storages as $name => $storage) {
            $container->setParameter(sprintf($storageParamPattern, $name), $storage['driver']);
            $container->setParameter(sprintf($driverParamPattern, $name, $storage['driver']), true);

            Storage::set($name, $storage['driver']);
        }
    }
}
