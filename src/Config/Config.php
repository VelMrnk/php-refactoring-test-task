<?php

namespace App\Config;

final class Config
{
    private array $props = [];

    public function set(string $key, string $val)
    {
        $this->props[$key] = $val;
    }

    public function get(string $key)
    {
        return $this->props[$key];
    }
}
