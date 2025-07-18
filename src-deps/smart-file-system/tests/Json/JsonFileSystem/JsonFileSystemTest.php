<?php

declare(strict_types=1);

namespace Symplify\SmartFileSystem\Tests\Json\JsonFileSystem;

use PHPUnit\Framework\TestCase;
use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\Json\JsonFileSystem;
use Symplify\SmartFileSystem\SmartFileSystem;

final class JsonFileSystemTest extends TestCase
{
    /**
     * @var string
     */
    private const TEMPORARY_FILE_PATH = __DIR__ . '/temppath.json';

    private JsonFileSystem $jsonFileSystem;

    private SmartFileSystem $smartFileSystem;

    protected function setUp(): void
    {
        $this->smartFileSystem = new SmartFileSystem();
        $this->jsonFileSystem = new JsonFileSystem(new FileSystemGuard(), $this->smartFileSystem);
    }

    public function testLoadFilePathToJson(): void
    {
        $loadedArray = $this->jsonFileSystem->loadFilePathToJson(__DIR__ . '/Fixture/some.json');
        $this->assertSame([
            'key' => 'value',
        ], $loadedArray);
    }

    public function testWriteJsonToFilePath(): void
    {
        $this->jsonFileSystem->writeJsonToFilePath([
            'another' => 'time',
        ], self::TEMPORARY_FILE_PATH);
        $this->assertFileEquals(__DIR__ . '/Fixture/expected_printed_json.json', self::TEMPORARY_FILE_PATH);

        $this->smartFileSystem->remove(self::TEMPORARY_FILE_PATH);
    }

    public function testMergeArrayToJsonFile(): void
    {
        $originalFile = __DIR__ . '/Fixture/some.json';
        $temporaryFile = __DIR__ . '/temppath.json';

        // backup of original file
        $this->smartFileSystem->copy($originalFile, $temporaryFile);

        $this->jsonFileSystem->mergeArrayToJsonFile($originalFile, [
            'one' => 'more',
        ]);
        $this->assertFileEquals(__DIR__ . '/Fixture/expected_merged_json.json', $originalFile);

        // restore original file
        $this->smartFileSystem->copy($temporaryFile, $originalFile, true);

        // cleanup temp file
        $this->smartFileSystem->remove($temporaryFile);
    }
}
