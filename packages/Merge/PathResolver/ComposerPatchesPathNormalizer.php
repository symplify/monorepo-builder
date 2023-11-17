<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\PathResolver;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerPathNormalizerInterface;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\Package\PackageComposerJsonMergerTest
 */
final class ComposerPatchesPathNormalizer implements ComposerPathNormalizerInterface
{
    /**
     * @var string[]
     */
    private const SECTIONS_WITH_PATH = ['patches'];
    /**
     *
     */
    public function normalizePaths(ComposerJson $packageComposerJson, SmartFileInfo $packageFile) : void
    {
        $extra = $this->normalizeExtraArray($packageFile, $packageComposerJson->getExtra());
        $packageComposerJson->setExtra($extra);
    }
    /**
     * @param array<string, mixed> $extraArray
     * @return array<string, mixed>
     */
    private function normalizeExtraArray(SmartFileInfo $packageFile, array $extraArray) : array
    {
        foreach (self::SECTIONS_WITH_PATH as $sectionWithPath) {
            if (!isset($extraArray[$sectionWithPath])) {
                continue;
            }
            $extraArray[$sectionWithPath] = $this->relativizePath($extraArray[$sectionWithPath], $packageFile);
        }
        return $extraArray;
    }
    /**
     * @param mixed[] $patchesSubSection
     * @return mixed[]
     */
    private function relativizePath(array $patchesSubSection, SmartFileInfo $packageFileInfo) : array
    {
        $packageRelativeDirectory = \dirname($packageFileInfo->getRelativeFilePathFromDirectory(\getcwd()));
        foreach ($patchesSubSection as $key => $value) {
            if (\is_array($value)) {
                $patchesSubSection[$key] = \array_map(function ($path) use($packageRelativeDirectory) : string {
                    return $this->relativizeSinglePath($packageRelativeDirectory, $path);
                }, $value);
            }
        }
        return $patchesSubSection;
    }
    private function relativizeSinglePath(string $packageRelativeDirectory, string $path) : string
    {
        if (\strncmp($path, 'vendor/', \strlen('vendor/')) === 0 || \strncmp($path, './vendor/', \strlen('./vendor/')) === 0) {
            return \preg_replace('#^(\\./)?vendor/[^/]+/[^/]+#', $packageRelativeDirectory, $path);
        }
        return $path;
    }
}
