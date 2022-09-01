<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder;

use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileInfo;
final class DependencyUpdater
{
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(JsonFileManager $jsonFileManager)
    {
        $this->jsonFileManager = $jsonFileManager;
    }
    /**
     * @param SmartFileInfo[] $smartFileInfos
     * @param string[] $packageNames
     */
    public function updateFileInfosWithPackagesAndVersion(array $smartFileInfos, array $packageNames, string $version) : void
    {
        foreach ($smartFileInfos as $smartFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($smartFileInfo);
            $json = $this->processSectionWithPackages($json, $packageNames, $version, ComposerJsonSection::REQUIRE);
            $json = $this->processSectionWithPackages($json, $packageNames, $version, ComposerJsonSection::REQUIRE_DEV);
            $this->jsonFileManager->printJsonToFileInfo($json, $smartFileInfo);
        }
    }
    /**
     * @param SmartFileInfo[] $smartFileInfos
     */
    public function updateFileInfosWithVendorAndVersion(array $smartFileInfos, string $vendor, string $version) : void
    {
        foreach ($smartFileInfos as $smartFileInfo) {
            $json = $this->jsonFileManager->loadFromFileInfo($smartFileInfo);
            $json = $this->processSection($json, $vendor, $version, ComposerJsonSection::REQUIRE);
            $json = $this->processSection($json, $vendor, $version, ComposerJsonSection::REQUIRE_DEV);
            $this->jsonFileManager->printJsonToFileInfo($json, $smartFileInfo);
        }
    }
    /**
     * @param mixed[] $json
     * @param string[] $parentPackageNames
     * @param ComposerJsonSection::* $section
     * @return mixed[]
     */
    private function processSectionWithPackages(array $json, array $parentPackageNames, string $targetVersion, string $section) : array
    {
        if (!isset($json[$section])) {
            return $json;
        }
        $packageNames = \array_keys($json[$section]);
        foreach ($packageNames as $packageName) {
            if (!\in_array($packageName, $parentPackageNames, \true)) {
                continue;
            }
            $json[$section][$packageName] = $targetVersion;
        }
        return $json;
    }
    /**
     * @param mixed[] $json
     * @param ComposerJsonSection::* $section
     * @return mixed[]
     */
    private function processSection(array $json, string $vendor, string $targetVersion, string $section) : array
    {
        if (!isset($json[$section])) {
            return $json;
        }
        foreach ($json[$section] as $packageName => $packageVersion) {
            if ($this->shouldSkip($vendor, $targetVersion, $packageName, $packageVersion)) {
                continue;
            }
            $json[$section][$packageName] = $targetVersion;
        }
        return $json;
    }
    private function shouldSkip(string $vendor, string $targetVersion, string $packageName, string $packageVersion) : bool
    {
        if (\strncmp($packageName, $vendor, \strlen($vendor)) !== 0) {
            return \true;
        }
        return $packageVersion === $targetVersion;
    }
}
