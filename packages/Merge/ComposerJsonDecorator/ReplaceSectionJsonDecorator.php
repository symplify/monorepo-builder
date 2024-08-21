<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
final class ReplaceSectionJsonDecorator implements ComposerJsonDecoratorInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector
     */
    private $mergedPackagesCollector;
    public function __construct(MergedPackagesCollector $mergedPackagesCollector)
    {
        $this->mergedPackagesCollector = $mergedPackagesCollector;
    }
    public function decorate(ComposerJson $composerJson) : void
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
