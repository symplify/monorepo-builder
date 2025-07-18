<?php

declare(strict_types=1);

namespace Symplify\SmartFileSystem\Json;

use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * @api
 * @see \Symplify\SmartFileSystem\Tests\Json\JsonFileSystem\JsonFileSystemTest
 */
final class JsonFileSystem
{
    public function __construct(
        private readonly FileSystemGuard $fileSystemGuard,
        private readonly SmartFileSystem $smartFileSystem
    ) {
    }

    /**
     * @return mixed[]
     */
    public function loadFilePathToJson(string $filePath): array
    {
        $this->fileSystemGuard->ensureFileExists($filePath, __METHOD__);

        $fileContent = $this->smartFileSystem->readFile($filePath);
        return Json::decode($fileContent, Json::FORCE_ARRAY);
    }

    /**
     * @param array<string, mixed> $jsonArray
     */
    public function writeJsonToFilePath(array $jsonArray, string $filePath): void
    {
        $jsonContent = Json::encode($jsonArray, Json::PRETTY) . PHP_EOL;
        $this->smartFileSystem->dumpFile($filePath, $jsonContent);
    }

    /**
     * @param array<string, mixed> $newJsonArray
     */
    public function mergeArrayToJsonFile(string $filePath, array $newJsonArray): void
    {
        $jsonArray = $this->loadFilePathToJson($filePath);

        $newComposerJsonArray = Arrays::mergeTree($jsonArray, $newJsonArray);

        $this->writeJsonToFilePath($newComposerJsonArray, $filePath);
    }
}
