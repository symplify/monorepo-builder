<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing\PackageDependency;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Package\PackageNamesProvider;
final class UsedPackagesResolver
{
    /**
     * @var \Symplify\MonorepoBuilder\Package\PackageNamesProvider
     */
    private $packageNamesProvider;
    public function __construct(\Symplify\MonorepoBuilder\Package\PackageNamesProvider $packageNamesProvider)
    {
        $this->packageNamesProvider = $packageNamesProvider;
    }
    /**
     * @param mixed[] $packageComposerJson
     * @return string[]
     */
    public function resolveForPackage(array $packageComposerJson) : array
    {
        $usedPackageNames = [];
        foreach ([\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE_DEV] as $section) {
            if (!isset($packageComposerJson[$section])) {
                continue;
            }
            $packageNames = \array_keys($packageComposerJson[$section]);
            foreach ($packageNames as $packageName) {
                if (!\in_array($packageName, $this->packageNamesProvider->provide(), \true)) {
                    continue;
                }
                $usedPackageNames[] = $packageName;
            }
        }
        return $usedPackageNames;
    }
}
