<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;

final class FilterOutDuplicatedRequireAndRequireDevJsonDecorator implements ComposerJsonDecoratorInterface
{
    public function decorate(ComposerJson $composerJson): void
    {
        if ($composerJson->getRequire() === []) {
            return;
        }

        if ($composerJson->getRequireDev() === []) {
            return;
        }

        $requirePackageNames = array_keys($composerJson->getRequire());
        $currentRequireDev = $composerJson->getRequireDev();

        foreach ($requirePackageNames as $packageName) {
            unset($currentRequireDev[$packageName]);
        }

        $composerJson->setRequireDev($currentRequireDev);
    }
}
