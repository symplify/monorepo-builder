<?php

declare (strict_types=1);
namespace MonorepoBuilder20220111\Symplify\ComposerJsonManipulator\Printer;

use MonorepoBuilder20220111\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder20220111\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20220111\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @api
 */
final class ComposerJsonPrinter
{
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(\MonorepoBuilder20220111\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager)
    {
        $this->jsonFileManager = $jsonFileManager;
    }
    public function printToString(\MonorepoBuilder20220111\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : string
    {
        return $this->jsonFileManager->encodeJsonToFileContent($composerJson->getJsonArray());
    }
    /**
     * @param \Symplify\SmartFileSystem\SmartFileInfo|string $targetFile
     */
    public function print(\MonorepoBuilder20220111\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, $targetFile) : string
    {
        if (\is_string($targetFile)) {
            return $this->jsonFileManager->printComposerJsonToFilePath($composerJson, $targetFile);
        }
        return $this->jsonFileManager->printJsonToFileInfo($composerJson->getJsonArray(), $targetFile);
    }
}
