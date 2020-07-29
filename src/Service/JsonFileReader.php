<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonFileReader implements JsonFileReaderInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->validateFileExists();
    }

    public function getContent(): string
    {
        return file_get_contents($this->path);
    }

    public function toArray(): array
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

    private function validateFileExists()
    {
        if (file_exists($this->path) === false) {
            throw new FileNotFoundException(
                'File was not found. Please make sure file name/path are correct and the file exists.'
            );
        }
    }
}