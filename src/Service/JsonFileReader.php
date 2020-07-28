<?php

namespace App\Service;

class JsonFileReader implements JsonFileReaderInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getContent(): string
    {
        return file_get_contents($this->path);
    }

    public function convertDataToArray(): array
    {
        $items = [];

        $content = $this->getContent();

        if (strlen($content) > 0) {
            $lines = explode("\n", $this->getContent());

            foreach ($lines as $line) {
                $items[] = json_decode($line, true);
            }
        }

        return $items;
    }
}