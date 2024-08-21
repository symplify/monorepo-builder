<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerPathNormalizerInterface;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ComposerJsonMerger
{
    /**
     * @param ComposerKeyMergerInterface[] $composerKeyMergers
     * @param ComposerPathNormalizerInterface[] $composerPathNormalizers
     */
    public function __construct(
        private ComposerJsonFactory $composerJsonFactory,
        private MergedPackagesCollector $mergedPackagesCollector,
        private array $composerKeyMergers,
        private array $composerPathNormalizers
    ) {
    }

    /**
     * @param SmartFileInfo[] $composerPackagesFileInfos
     */
    public function mergeFileInfos(ComposerJson $mainComposerJson,array $composerPackagesFileInfos): ComposerJson
    {
        foreach ($composerPackagesFileInfos as $composerPackageFileInfo) {
            $packageComposerJson = $this->composerJsonFactory->createFromFileInfo($composerPackageFileInfo);

            $this->mergeJsonToRootWithPackageFileInfo(
                $mainComposerJson,
                $packageComposerJson,
                $composerPackageFileInfo
            );
        }

        return $mainComposerJson;
    }

    public function mergeJsonToRoot(ComposerJson $mainComposerJson, ComposerJson $newComposerJson): void
    {
        $name = $newComposerJson->getName();
        if ($name !== null) {
            $this->mergedPackagesCollector->addPackage($name);
        }

        foreach ($this->composerKeyMergers as $composerKeyMerger) {
            $composerKeyMerger->merge($mainComposerJson, $newComposerJson);
        }
    }

    private function mergeJsonToRootWithPackageFileInfo(
        ComposerJson $mainComposerJson,
        ComposerJson $newComposerJson,
        SmartFileInfo $packageFileInfo
    ): void {
        // prepare paths before autolaod merging
        foreach ($this->composerPathNormalizers as $composerPathNormalizer) {
            $composerPathNormalizer->normalizePaths($newComposerJson, $packageFileInfo);
        }

        $this->mergeJsonToRoot($mainComposerJson, $newComposerJson);
    }
}
