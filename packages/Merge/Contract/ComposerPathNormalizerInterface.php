<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\Contract;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\SmartFileSystem\SmartFileInfo;

interface ComposerPathNormalizerInterface
{
    public function normalizePaths(ComposerJson $packageComposerJson, SmartFileInfo $packageFile): void;
}
