<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
/**
 * Remove inter-dependencies in split packages from root, e.g. symfony/console needs symfony/filesystem in package, but
 * it makes no sense to have symfony/filesystem in root of symfony/symfony.
 *
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\RootRemoveComposerJsonDecorator\RootRemoveComposerJsonDecoratorTest
 */
final class RootRemoveComposerJsonDecorator implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector
     */
    private $mergedPackagesCollector;
    public function __construct(\Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector $mergedPackagesCollector)
    {
        $this->mergedPackagesCollector = $mergedPackagesCollector;
    }
    public function decorate(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
    {
        $require = $this->filterOutMergedPackages($composerJson->getRequire());
        $composerJson->setRequire($require);
        $requireDev = $this->filterOutMergedPackages($composerJson->getRequireDev());
        $composerJson->setRequireDev($requireDev);
    }
    /**
     * @param mixed[] $require
     * @return mixed[]
     */
    private function filterOutMergedPackages(array $require) : array
    {
        $packageNames = \array_keys($require);
        foreach ($packageNames as $packageName) {
            if (!\in_array($packageName, $this->mergedPackagesCollector->getPackages(), \true)) {
                continue;
            }
            unset($require[$packageName]);
        }
        return $require;
    }
}
