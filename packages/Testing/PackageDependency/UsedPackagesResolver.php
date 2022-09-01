<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Testing\PackageDependency;

use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Package\PackageNamesProvider;
final class UsedPackagesResolver
{
    /**
     * @var \Symplify\MonorepoBuilder\Package\PackageNamesProvider
     */
    private $packageNamesProvider;
    public function __construct(PackageNamesProvider $packageNamesProvider)
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
        foreach ([ComposerJsonSection::REQUIRE, ComposerJsonSection::REQUIRE_DEV] as $section) {
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
