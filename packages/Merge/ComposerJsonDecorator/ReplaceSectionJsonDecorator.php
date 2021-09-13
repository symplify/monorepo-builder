<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
final class ReplaceSectionJsonDecorator implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector
     */
    private $mergedPackagesCollector;
    public function __construct(\Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector $mergedPackagesCollector)
    {
        $this->mergedPackagesCollector = $mergedPackagesCollector;
    }
    /**
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson
     */
    public function decorate($composerJson) : void
    {
        $mergedPackages = $this->mergedPackagesCollector->getPackages();
        foreach ($mergedPackages as $mergedPackage) {
            // prevent value override
            if ($composerJson->isReplacePackageSet($mergedPackage)) {
                continue;
            }
            $composerJson->setReplacePackage($mergedPackage, 'self.version');
        }
    }
}
