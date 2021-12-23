<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\ComposerJsonManipulator;

use MonorepoBuilder20211223\Nette\Utils\Json;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @api
 * @see \Symplify\ComposerJsonManipulator\Tests\ComposerJsonFactory\ComposerJsonFactoryTest
 */
final class ComposerJsonFactory
{
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager)
    {
        $this->jsonFileManager = $jsonFileManager;
    }
    public function createFromString(string $jsonString) : \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $jsonArray = \MonorepoBuilder20211223\Nette\Utils\Json::decode($jsonString, \MonorepoBuilder20211223\Nette\Utils\Json::FORCE_ARRAY);
        return $this->createFromArray($jsonArray);
    }
    public function createFromFileInfo(\MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $jsonArray = $this->jsonFileManager->loadFromFilePath($smartFileInfo->getRealPath());
        $composerJson = $this->createFromArray($jsonArray);
        $composerJson->setOriginalFileInfo($smartFileInfo);
        return $composerJson;
    }
    public function createFromFilePath(string $filePath) : \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $jsonArray = $this->jsonFileManager->loadFromFilePath($filePath);
        $composerJson = $this->createFromArray($jsonArray);
        $fileInfo = new \MonorepoBuilder20211223\Symplify\SmartFileSystem\SmartFileInfo($filePath);
        $composerJson->setOriginalFileInfo($fileInfo);
        return $composerJson;
    }
    public function createEmpty() : \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        return new \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
    }
    /**
     * @param mixed[] $jsonArray
     */
    public function createFromArray(array $jsonArray) : \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $composerJson = new \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFIG])) {
            $composerJson->setConfig($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFIG]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::NAME])) {
            $composerJson->setName($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::NAME]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::TYPE])) {
            $composerJson->setType($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::TYPE]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTHORS])) {
            $composerJson->setAuthors($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTHORS]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::DESCRIPTION])) {
            $composerJson->setDescription($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::DESCRIPTION]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::KEYWORDS])) {
            $composerJson->setKeywords($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::KEYWORDS]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::HOMEPAGE])) {
            $composerJson->setHomepage($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::HOMEPAGE]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::LICENSE])) {
            $composerJson->setLicense($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::LICENSE]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::BIN])) {
            $composerJson->setBin($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::BIN]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE])) {
            $composerJson->setRequire($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE_DEV])) {
            $composerJson->setRequireDev($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE_DEV]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD])) {
            $composerJson->setAutoload($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD_DEV])) {
            $composerJson->setAutoloadDev($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD_DEV]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPLACE])) {
            $composerJson->setReplace($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPLACE]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::EXTRA])) {
            $composerJson->setExtra($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::EXTRA]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS])) {
            $composerJson->setScripts($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS_DESCRIPTIONS])) {
            $composerJson->setScriptsDescriptions($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS_DESCRIPTIONS]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SUGGEST])) {
            $composerJson->setSuggest($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SUGGEST]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::MINIMUM_STABILITY])) {
            $composerJson->setMinimumStability($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::MINIMUM_STABILITY]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PREFER_STABLE])) {
            $composerJson->setPreferStable($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PREFER_STABLE]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFLICT])) {
            $composerJson->setConflicts($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFLICT]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPOSITORIES])) {
            $composerJson->setRepositories($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPOSITORIES]);
        }
        if (isset($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::VERSION])) {
            $composerJson->setVersion($jsonArray[\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::VERSION]);
        }
        $orderedKeys = \array_keys($jsonArray);
        $composerJson->setOrderedKeys($orderedKeys);
        return $composerJson;
    }
}
