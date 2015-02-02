<?php

namespace Akeneo\Bundle\StorageUtilsBundle\DependencyInjection;

use Akeneo\Component\StorageUtils\Storage;
use Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class StorageHelper
{
    const DOCTRINE_ORM_MAPPINGS_PASS = '\Oro\Bundle\EntityBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass';
    const DOCTRINE_MONGODB_MAPPINGS_PASS = '\Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass';

    /** @var ContainerBuilder */
    protected $container;

    /**
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * Set a new storage with the provided driver. The parameter  akeneo_storage_utils.storage_driver.STORAGE.DRIVER
     * is also added to the container.
     *
     * @param string $storage
     * @param string $driver
     */
    public function set($storage, $driver)
    {
        Storage::set($storage, $driver);
        $this->container->setParameter($this->getContainerParameterName($storage, $driver), true);
    }

    /**
     * Load the configuration files of the storage.
     *
     * @param string $storage
     * @param string $path
     */
    public function loadStorageConfigFiles($storage, $path)
    {
        $driver = Storage::get($storage);

        $loader = new YamlFileLoader($this->container, new FileLocator($path . '/../Resources/config'));
        $loader->load(sprintf('storage_driver/%s.yml', $driver));
    }

    /**
     * Get the Doctrine mapping pass corresponding to the storage.
     *
     * @param string $storage
     * @param array  $mappings
     *
     * @return RegisterMappingsPass
     */
    public function getMappingsPass($storage, array $mappings/*, $isStorageSpecific = false*/)
    {
        $driver = Storage::get($storage);

        $managerParameters = ['doctrine.orm.entity_manager'];
        $mappingsPassClass = self::DOCTRINE_ORM_MAPPINGS_PASS;

        if (Storage::DRIVER_DOCTRINE_MONGODB_ODM === $driver) {
            $managerParameters = ['doctrine.odm.mongodb.document_manager'];
            $mappingsPassClass = self::DOCTRINE_MONGODB_MAPPINGS_PASS;
        }

        if (!class_exists($mappingsPassClass)) {
            throw new \RuntimeException(sprintf('The mapping class "%s" does not exist.', $mappingsPassClass));
        }

//        $containerParameter = $isStorageSpecific === true ?
//            $this->getContainerParameterName($storage, $driver) : false;

        return $mappingsPassClass::createYamlMappingDriver(
            $mappings,
            $managerParameters,
            $this->getContainerParameterName($storage, $driver)
//            $containerParameter
        );
    }

    /**
     * @param string $storage
     * @param string $driver
     *
     * @return string
     */
    private function getContainerParameterName($storage, $driver)
    {
        return sprintf('akeneo_storage_utils.storage_driver.%s.%s', $storage, $driver);
    }
}
