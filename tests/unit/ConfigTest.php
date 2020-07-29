<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Config\Config;

class ConfigTest extends TestCase
{
    public function testConfigUniqueInstance()
    {
        $config1 = Config::getInstance();
        $config2 = Config::getInstance();

        $this->assertEquals($config1, $config2);
    }

    public function testConfigObject()
    {
        $config = Config::getInstance();
        $config->setProperty('env', 'dev');

        $this->assertEquals('dev', $config->getProperty('env'));
    }
}