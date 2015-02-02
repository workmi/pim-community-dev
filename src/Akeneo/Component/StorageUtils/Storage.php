<?php

namespace Akeneo\Component\StorageUtils;

final class Storage
{
    const DRIVER_DOCTRINE_ORM = 'doctrine/orm';
    const DRIVER_DOCTRINE_MONGODB_ODM = 'doctrine/mongodb-odm';

    /** @var array List of storages. The key is the name of the storage and the value is the driver used. */
    private static $storages = [];

    /**
     * @param string $storage
     *
     * @return string
     */
    public static function get($storage)
    {
        if (!self::has($storage)) {
            throw new \InvalidArgumentException(sprintf('The storage "%s" has not been registered.', $storage));
        }

        return self::$storages[$storage];
    }

    /**
     * @param string $storage
     * @param string $driver
     */
    public static function set($storage, $driver)
    {
        if (!in_array($driver, self::getDrivers())) {
            throw new \InvalidArgumentException(sprintf('The driver "%s" is not handled.', $driver));
        }

        self::$storages[$storage] = $driver;
    }

    /**
     * @param $storage
     *
     * @return bool
     */
    public static function has($storage)
    {
        return array_key_exists($storage, self::$storages);
    }

    /**
     * Provides the supported drivers for application storage
     *
     * @return string[]
     */
    public static function getDrivers()
    {
        return [self::DRIVER_DOCTRINE_ORM, self::DRIVER_DOCTRINE_MONGODB_ODM];
    }
}
