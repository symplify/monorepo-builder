<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Contract;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilderPrefix202311\Symplify\SmartFileSystem\SmartFileInfo;
interface ComposerPathNormalizerInterface
{
    public function normalizePaths(ComposerJson $packageComposerJson, SmartFileInfo $packageFile) : void;
}
