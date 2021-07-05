<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
final class ReplaceSectionJsonDecorator implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
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
