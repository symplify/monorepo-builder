<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
/**
 * Remove inter-dependencies in split packages from root, e.g. symfony/console needs symfony/filesystem in package, but
 * it makes no sense to have symfony/filesystem in root of symfony/symfony.
 *
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\RootRemoveComposerJsonDecorator\RootRemoveComposerJsonDecoratorTest
 */
final class RootRemoveComposerJsonDecorator implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector
     */
    private $mergedPackagesCollector;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector $mergedPackagesCollector)
    {
        $this->mergedPackagesCollector = $mergedPackagesCollector;
    }
    public function decorate(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
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
        $requireKeys = \array_keys($require);
        foreach ($requireKeys as $packageName) {
            if (!\in_array($packageName, $this->mergedPackagesCollector->getPackages(), \true)) {
                continue;
            }
            unset($require[$packageName]);
        }
        return $require;
    }
}
