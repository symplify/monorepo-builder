<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
final class FilterOutDuplicatedRequireAndRequireDevJsonDecorator implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    public function decorate(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
    {
        if ($composerJson->getRequire() === []) {
            return;
        }
        if ($composerJson->getRequireDev() === []) {
            return;
        }
        $duplicatedPackages = $composerJson->getDuplicatedRequirePackages();
        $currentRequireDev = $composerJson->getRequireDev();
        $currentRequireDevKeys = \array_keys($currentRequireDev);
        foreach ($currentRequireDevKeys as $package) {
            if (\in_array($package, $duplicatedPackages, \true)) {
                unset($currentRequireDev[$package]);
            }
        }
        $composerJson->setRequireDev($currentRequireDev);
    }
}
