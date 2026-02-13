<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerPathNormalizerInterface;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use Symplify\SmartFileSystem\SmartFileInfo;

final readonly class ComposerJsonMerger
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

        // packages already in require don't need to be in require-dev
        $this->filterOutDuplicatedRequireDev($mainComposerJson);

        $newExtras = $newComposerJson->getExtraSections();
        if ($newExtras !== []) {
            $mainExtras = $mainComposerJson->getExtraSections();
            $mainComposerJson->setExtraSections(array_merge($mainExtras, $newExtras));

            $currentKeys = $mainComposerJson->getJsonKeys();
            foreach (array_keys($newExtras) as $extraKey) {
                if (!in_array($extraKey, $currentKeys, true)) {
                    $currentKeys[] = $extraKey;
                }
            }

            $mainComposerJson->setJsonKeys($currentKeys);
        }
    }

    private function filterOutDuplicatedRequireDev(ComposerJson $composerJson): void
    {
        $requirePackageNames = array_keys($composerJson->getRequire());
        $requireDev = $composerJson->getRequireDev();

        foreach ($requirePackageNames as $requirePackageName) {
            unset($requireDev[$requirePackageName]);
        }

        $composerJson->setRequireDev($requireDev);
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
