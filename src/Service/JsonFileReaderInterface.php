<?php

namespace App\Service;

interface JsonFileReaderInterface
{
    public function getContent(): string;
    public function convertDataToArray(): array;
}
