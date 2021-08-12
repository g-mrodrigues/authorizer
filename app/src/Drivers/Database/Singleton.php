<?php

namespace App\Drivers\Database;

use \Exception;

class Singleton
{
    private static ?self $instances = null;

    /**
     * Singleton's constructor should not be public
     */
    private function __construct() {}

    /**
     * Cloning are not permitted for singletons.
     */
    public function __clone() {
        throw new Exception('Cannot clone singleton');
    }

    /**
     * Unserialization are not permitted for singletons.
     */
    public function __wakeup()
    {
        throw new Exception('Cannot unserialize singleton');
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instances)) {
            self::$instances = new static();
        }

        return self::$instances;
    }

    public static function reset()
    {
        self::$instances = null;
    }
}