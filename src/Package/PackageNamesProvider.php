<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Package;

use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder202209\Symplify\SmartFileSystem\SmartFileInfo;
final class PackageNamesProvider
{
    /**
     * @var string[]
     */
    private $names = [];
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(ComposerJsonProvider $composerJsonProvider, JsonFileManager $jsonFileManager)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->jsonFileManager = $jsonFileManager;
    }
    /**
     * @return string[]
     */
    public function provide() : array
    {
        if ($this->names !== []) {
            return $this->names;
        }
        $packagesFileInfos = $this->composerJsonProvider->getPackagesComposerFileInfos();
        foreach ($packagesFileInfos as $packageFileInfo) {
            $name = $this->extractNameFromFileInfo($packageFileInfo);
            if ($name !== null) {
                $this->names[] = $name;
            }
        }
        return $this->names;
    }
    private function extractNameFromFileInfo(SmartFileInfo $smartFileInfo) : ?string
    {
        $json = $this->jsonFileManager->loadFromFileInfo($smartFileInfo);
        return $json['name'] ?? null;
    }
}
