<?php

declare (strict_types=1);
namespace MonorepoBuilder20210703\Symplify\MonorepoBuilder\Json;

use MonorepoBuilder20210703\Symplify\MonorepoBuilder\Package\PackageProvider;
final class PackageJsonProvider
{
    /**
     * @var \Symplify\MonorepoBuilder\Package\PackageProvider
     */
    private $packageProvider;
    public function __construct(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\Package\PackageProvider $packageProvider)
    {
        $this->packageProvider = $packageProvider;
    }
    /**
     * @return string[]
     */
    public function providePackages() : array
    {
        $packageShortNames = [];
        foreach ($this->packageProvider->provide() as $package) {
            $packageShortNames[] = $package->getShortName();
        }
        return $packageShortNames;
    }
    /**
     * @return string[]
     */
    public function providePackagesWithTests() : array
    {
        $packageShortNames = [];
        foreach ($this->packageProvider->provide() as $package) {
            if (!$package->hasTests()) {
                continue;
            }
            $packageShortNames[] = $package->getShortName();
        }
        return $packageShortNames;
    }
}
