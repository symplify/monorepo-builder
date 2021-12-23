<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem;

use MonorepoBuilder20211223\Nette\Utils\Json;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\Json\JsonCleaner;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\Json\JsonInliner;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Configuration\StaticEolConfiguration;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem;
/**
 * @see \Symplify\MonorepoBuilder\Tests\FileSystem\JsonFileManager\JsonFileManagerTest
 */
final class JsonFileManager
{
    /**
     * @var mixed[]
     */
    private $cachedJSONFiles = [];
    /**
     * @var \Symplify\SmartFileSystem\SmartFileSystem
     */
    private $smartFileSystem;
    /**
     * @var \Symplify\ComposerJsonManipulator\Json\JsonCleaner
     */
    private $jsonCleaner;
    /**
     * @var \Symplify\ComposerJsonManipulator\Json\JsonInliner
     */
    private $jsonInliner;
    public function __construct(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\Json\JsonCleaner $jsonCleaner, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\Json\JsonInliner $jsonInliner)
    {
        $this->smartFileSystem = $smartFileSystem;
        $this->jsonCleaner = $jsonCleaner;
        $this->jsonInliner = $jsonInliner;
    }
    /**
     * @return mixed[]
     */
    public function loadFromFileInfo(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : array
    {
        $realPath = $smartFileInfo->getRealPath();
        if (!isset($this->cachedJSONFiles[$realPath])) {
            $this->cachedJSONFiles[$realPath] = \MonorepoBuilder20211223\Nette\Utils\Json::decode($smartFileInfo->getContents(), \MonorepoBuilder20211223\Nette\Utils\Json::FORCE_ARRAY);
        }
        return $this->cachedJSONFiles[$realPath];
    }
    /**
     * @return array<string, mixed>
     */
    public function loadFromFilePath(string $filePath) : array
    {
        $fileContent = $this->smartFileSystem->readFile($filePath);
        return \MonorepoBuilder20211223\Nette\Utils\Json::decode($fileContent, \MonorepoBuilder20211223\Nette\Utils\Json::FORCE_ARRAY);
    }
    /**
     * @param mixed[] $json
     */
    public function printJsonToFileInfo(array $json, \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : string
    {
        $jsonString = $this->encodeJsonToFileContent($json);
        $this->smartFileSystem->dumpFile($smartFileInfo->getPathname(), $jsonString);
        $realPath = $smartFileInfo->getRealPath();
        unset($this->cachedJSONFiles[$realPath]);
        return $jsonString;
    }
    public function printComposerJsonToFilePath(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, string $filePath) : string
    {
        $jsonString = $this->encodeJsonToFileContent($composerJson->getJsonArray());
        $this->smartFileSystem->dumpFile($filePath, $jsonString);
        return $jsonString;
    }
    /**
     * @param mixed[] $json
     */
    public function encodeJsonToFileContent(array $json) : string
    {
        // Empty arrays may lead to bad encoding since we can't be sure whether they need to be arrays or objects.
        $json = $this->jsonCleaner->removeEmptyKeysFromJsonArray($json);
        $jsonContent = \MonorepoBuilder20211223\Nette\Utils\Json::encode($json, \MonorepoBuilder20211223\Nette\Utils\Json::PRETTY) . \MonorepoBuilder20211223\Symplify\PackageBuilder\Configuration\StaticEolConfiguration::getEolChar();
        return $this->jsonInliner->inlineSections($jsonContent);
    }
}
