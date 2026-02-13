<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\ComposerJsonManipulator;

use Nette\Utils\Json;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @api
 * @see \Symplify\MonorepoBuilder\Tests\ComposerJsonManipulator\ComposerJsonFactory\ComposerJsonFactoryTest
 */
final readonly class ComposerJsonFactory
{
    public function __construct(
        private JsonFileManager $jsonFileManager
    ) {
    }

    public function createFromString(string $jsonString): ComposerJson
    {
        $jsonArray = Json::decode($jsonString, Json::FORCE_ARRAY);
        return $this->createFromArray($jsonArray);
    }

    public function createFromFileInfo(SmartFileInfo $smartFileInfo): ComposerJson
    {
        $jsonArray = $this->jsonFileManager->loadFromFilePath($smartFileInfo->getRealPath());

        $composerJson = $this->createFromArray($jsonArray);
        $composerJson->setOriginalFileInfo($smartFileInfo);

        return $composerJson;
    }

    public function createFromFilePath(string $filePath): ComposerJson
    {
        $jsonArray = $this->jsonFileManager->loadFromFilePath($filePath);

        $composerJson = $this->createFromArray($jsonArray);
        $fileInfo = new SmartFileInfo($filePath);
        $composerJson->setOriginalFileInfo($fileInfo);

        return $composerJson;
    }

    public function createEmpty(): ComposerJson
    {
        return new ComposerJson();
    }

    /**
     * @param mixed[] $jsonArray
     */
    public function createFromArray(array $jsonArray): ComposerJson
    {
        $composerJson = new ComposerJson();
        $remainingKeys = $jsonArray;

        if (isset($jsonArray[ComposerJsonSection::CONFIG])) {
            $composerJson->setConfig($jsonArray[ComposerJsonSection::CONFIG]);
            unset($remainingKeys[ComposerJsonSection::CONFIG]);
        }

        if (isset($jsonArray[ComposerJsonSection::NAME])) {
            $composerJson->setName($jsonArray[ComposerJsonSection::NAME]);
            unset($remainingKeys[ComposerJsonSection::NAME]);
        }

        if (isset($jsonArray[ComposerJsonSection::TYPE])) {
            $composerJson->setType($jsonArray[ComposerJsonSection::TYPE]);
            unset($remainingKeys[ComposerJsonSection::TYPE]);
        }

        if (isset($jsonArray[ComposerJsonSection::AUTHORS])) {
            $composerJson->setAuthors($jsonArray[ComposerJsonSection::AUTHORS]);
            unset($remainingKeys[ComposerJsonSection::AUTHORS]);
        }

        if (isset($jsonArray[ComposerJsonSection::DESCRIPTION])) {
            $composerJson->setDescription($jsonArray[ComposerJsonSection::DESCRIPTION]);
            unset($remainingKeys[ComposerJsonSection::DESCRIPTION]);
        }

        if (isset($jsonArray[ComposerJsonSection::KEYWORDS])) {
            $composerJson->setKeywords($jsonArray[ComposerJsonSection::KEYWORDS]);
            unset($remainingKeys[ComposerJsonSection::KEYWORDS]);
        }

        if (isset($jsonArray[ComposerJsonSection::HOMEPAGE])) {
            $composerJson->setHomepage($jsonArray[ComposerJsonSection::HOMEPAGE]);
            unset($remainingKeys[ComposerJsonSection::HOMEPAGE]);
        }

        if (isset($jsonArray[ComposerJsonSection::LICENSE])) {
            $composerJson->setLicense($jsonArray[ComposerJsonSection::LICENSE]);
            unset($remainingKeys[ComposerJsonSection::LICENSE]);
        }

        if (isset($jsonArray[ComposerJsonSection::BIN])) {
            $composerJson->setBin($jsonArray[ComposerJsonSection::BIN]);
            unset($remainingKeys[ComposerJsonSection::BIN]);
        }

        if (isset($jsonArray[ComposerJsonSection::REQUIRE])) {
            $composerJson->setRequire($jsonArray[ComposerJsonSection::REQUIRE]);
            unset($remainingKeys[ComposerJsonSection::REQUIRE]);
        }

        if (isset($jsonArray[ComposerJsonSection::REQUIRE_DEV])) {
            $composerJson->setRequireDev($jsonArray[ComposerJsonSection::REQUIRE_DEV]);
            unset($remainingKeys[ComposerJsonSection::REQUIRE_DEV]);
        }

        if (isset($jsonArray[ComposerJsonSection::AUTOLOAD])) {
            $composerJson->setAutoload($jsonArray[ComposerJsonSection::AUTOLOAD]);
            unset($remainingKeys[ComposerJsonSection::AUTOLOAD]);
        }

        if (isset($jsonArray[ComposerJsonSection::AUTOLOAD_DEV])) {
            $composerJson->setAutoloadDev($jsonArray[ComposerJsonSection::AUTOLOAD_DEV]);
            unset($remainingKeys[ComposerJsonSection::AUTOLOAD_DEV]);
        }

        if (isset($jsonArray[ComposerJsonSection::REPLACE])) {
            $composerJson->setReplace($jsonArray[ComposerJsonSection::REPLACE]);
            unset($remainingKeys[ComposerJsonSection::REPLACE]);
        }

        if (isset($jsonArray[ComposerJsonSection::EXTRA])) {
            $composerJson->setExtra($jsonArray[ComposerJsonSection::EXTRA]);
            unset($remainingKeys[ComposerJsonSection::EXTRA]);
        }

        if (isset($jsonArray[ComposerJsonSection::SCRIPTS])) {
            $composerJson->setScripts($jsonArray[ComposerJsonSection::SCRIPTS]);
            unset($remainingKeys[ComposerJsonSection::SCRIPTS]);
        }

        if (isset($jsonArray[ComposerJsonSection::SCRIPTS_DESCRIPTIONS])) {
            $composerJson->setScriptsDescriptions($jsonArray[ComposerJsonSection::SCRIPTS_DESCRIPTIONS]);
            unset($remainingKeys[ComposerJsonSection::SCRIPTS_DESCRIPTIONS]);
        }

        if (isset($jsonArray[ComposerJsonSection::SUGGEST])) {
            $composerJson->setSuggest($jsonArray[ComposerJsonSection::SUGGEST]);
            unset($remainingKeys[ComposerJsonSection::SUGGEST]);
        }

        if (isset($jsonArray[ComposerJsonSection::MINIMUM_STABILITY])) {
            $composerJson->setMinimumStability($jsonArray[ComposerJsonSection::MINIMUM_STABILITY]);
            unset($remainingKeys[ComposerJsonSection::MINIMUM_STABILITY]);
        }

        if (isset($jsonArray[ComposerJsonSection::PREFER_STABLE])) {
            $composerJson->setPreferStable($jsonArray[ComposerJsonSection::PREFER_STABLE]);
            unset($remainingKeys[ComposerJsonSection::PREFER_STABLE]);
        }

        if (isset($jsonArray[ComposerJsonSection::CONFLICT])) {
            $composerJson->setConflicts($jsonArray[ComposerJsonSection::CONFLICT]);
            unset($remainingKeys[ComposerJsonSection::CONFLICT]);
        }

        if (isset($jsonArray[ComposerJsonSection::REPOSITORIES])) {
            $composerJson->setRepositories($jsonArray[ComposerJsonSection::REPOSITORIES]);
            unset($remainingKeys[ComposerJsonSection::REPOSITORIES]);
        }

        if (isset($jsonArray[ComposerJsonSection::VERSION])) {
            $composerJson->setVersion($jsonArray[ComposerJsonSection::VERSION]);
            unset($remainingKeys[ComposerJsonSection::VERSION]);
        }

        if (isset($jsonArray[ComposerJsonSection::PROVIDE])) {
            $composerJson->setProvide($jsonArray[ComposerJsonSection::PROVIDE]);
            unset($remainingKeys[ComposerJsonSection::PROVIDE]);
        }

        if (isset($jsonArray[ComposerJsonSection::FUNDING])) {
            $composerJson->setFunding($jsonArray[ComposerJsonSection::FUNDING]);
            unset($remainingKeys[ComposerJsonSection::FUNDING]);
        }

        if (isset($jsonArray[ComposerJsonSection::SUPPORT])) {
            $composerJson->setSupport($jsonArray[ComposerJsonSection::SUPPORT]);
            unset($remainingKeys[ComposerJsonSection::SUPPORT]);
        }

        $composerJson->setJsonKeys(array_keys($jsonArray));

        if ($remainingKeys !== []) {
            $composerJson->setExtraSections($remainingKeys);
        }

        return $composerJson;
    }
}
