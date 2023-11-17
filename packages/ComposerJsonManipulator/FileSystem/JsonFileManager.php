<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem;

use MonorepoBuilderPrefix202311\Nette\Utils\Json;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\Json\JsonCleaner;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\Json\JsonInliner;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileSystem;
/**
 * @see \Symplify\MonorepoBuilder\Tests\FileSystem\JsonFileManager\JsonFileManagerTest
 */
final class JsonFileManager
{
    /**
     * @var \Symplify\SmartFileSystem\SmartFileSystem
     */
    private $smartFileSystem;
    /**
     * @var \Symplify\MonorepoBuilder\ComposerJsonManipulator\Json\JsonCleaner
     */
    private $jsonCleaner;
    /**
     * @var \Symplify\MonorepoBuilder\ComposerJsonManipulator\Json\JsonInliner
     */
    private $jsonInliner;
    /**
     * @var array<string, mixed[]>
     */
    private $cachedJSONFiles = [];
    public function __construct(SmartFileSystem $smartFileSystem, JsonCleaner $jsonCleaner, JsonInliner $jsonInliner)
    {
        $this->smartFileSystem = $smartFileSystem;
        $this->jsonCleaner = $jsonCleaner;
        $this->jsonInliner = $jsonInliner;
    }
    /**
     * @return mixed[]
     */
    public function loadFromFileInfo(SmartFileInfo $smartFileInfo) : array
    {
        $realPath = $smartFileInfo->getRealPath();
        if (!isset($this->cachedJSONFiles[$realPath])) {
            $this->cachedJSONFiles[$realPath] = Json::decode($smartFileInfo->getContents(), Json::FORCE_ARRAY);
        }
        return $this->cachedJSONFiles[$realPath];
    }
    /**
     * @return array<string, mixed>
     */
    public function loadFromFilePath(string $filePath) : array
    {
        $fileContent = $this->smartFileSystem->readFile($filePath);
        return Json::decode($fileContent, Json::FORCE_ARRAY);
    }
    /**
     * @param mixed[] $json
     */
    public function printJsonToFileInfoAndReturn(array $json, SmartFileInfo $smartFileInfo) : string
    {
        $jsonString = $this->encodeJsonToFileContent($json);
        $this->printJsonStringToSmartFileInfo($smartFileInfo, $jsonString);
        return $jsonString;
    }
    /**
     * @param mixed[] $json
     */
    public function printJsonToFileInfo(array $json, SmartFileInfo $smartFileInfo) : void
    {
        $jsonString = $this->encodeJsonToFileContent($json);
        $this->printJsonStringToSmartFileInfo($smartFileInfo, $jsonString);
    }
    public function printComposerJsonToFilePath(ComposerJson $composerJson, string $filePath) : void
    {
        $jsonString = $this->encodeJsonToFileContent($composerJson->getJsonArray());
        $this->smartFileSystem->dumpFile($filePath, $jsonString);
    }
    /**
     * @param mixed[] $json
     */
    public function encodeJsonToFileContent(array $json) : string
    {
        // Empty arrays may lead to bad encoding since we can't be sure whether they need to be arrays or objects.
        $json = $this->jsonCleaner->removeEmptyKeysFromJsonArray($json);
        $jsonContent = Json::encode($json, Json::PRETTY) . \PHP_EOL;
        return $this->jsonInliner->inlineSections($jsonContent);
    }
    private function printJsonStringToSmartFileInfo(SmartFileInfo $smartFileInfo, string $jsonString) : void
    {
        $this->smartFileSystem->dumpFile($smartFileInfo->getPathname(), $jsonString);
        $realPath = $smartFileInfo->getRealPath();
        unset($this->cachedJSONFiles[$realPath]);
    }
}
