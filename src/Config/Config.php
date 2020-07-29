<?php

namespace App\Config;

final class Config
{
    private array $props = [];

    private static $instance;

    private function __construct() { }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Config();
        }

        return self::$instance;
    }

    public function setProperty(string $key, string $val)
    {
        $this->props[$key] = $val;
    }

    public function getProperty(string $key)
    {
        return $this->props[$key];
    }
}
