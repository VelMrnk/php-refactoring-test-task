<?php

namespace App\Tests;

use App\Service\JsonFileReader;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonFileReaderTest extends TestCase
{
    private $fileSystem;

    private const JSON_DATA = '{"bin":"45717360","amount":"100.00","currency":"EUR"}';

    public function setUp(): void
    {
        // define virtual file system
        $directory = [
            'json' => [
                'input.json' => self::JSON_DATA
            ]
        ];

        // setup and cache the virtual file system
        $this->fileSystem = vfsStream::setup('root', null, $directory);
        $this->assertTrue($this->fileSystem->hasChild('json/input.json'));
    }

    public function testFileNotExistsError()
    {
        $this->expectException(FileNotFoundException::class);
        $jsonReader = new JsonFileReader( $this->fileSystem->url() . '/no-file.txt');
    }

    public function testGetContentSuccess()
    {
        $jsonReader = new JsonFileReader( $this->fileSystem->url() . '/json/input.json');
        $this->assertEquals(self::JSON_DATA, $jsonReader->getContent());
    }

    public function testToArraySuccess()
    {
        $jsonReader = new JsonFileReader( $this->fileSystem->url() . '/json/input.json');
        $lines = explode("\n",  $jsonReader->getContent());

        $items = [];

        foreach ($lines as $line) {
            $items[] = json_decode($line, true);
        }

        $this->assertEquals($items, $jsonReader->toArray());
    }
}