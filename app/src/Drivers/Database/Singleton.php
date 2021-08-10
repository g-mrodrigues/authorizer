<?php

namespace App\Drivers\Database;

class Singleton
{
    private static array $instances = [];

    /**
     * Singleton's constructor should not be public
     */
    protected function __construct()
    {
    }

    /**
     * Cloning are not permitted for singletons.
     */
    protected function __clone()
    {
    }

    /**
     * Unserialization are not permitted for singletons.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    public static function getInstance()
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }
}