<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Kernel;

use MonorepoBuilder20211104\Psr\Container\ContainerInterface;
use MonorepoBuilder20211104\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonManipulatorConfig;
use MonorepoBuilder20211104\Symplify\ConsoleColorDiff\ValueObject\ConsoleColorDiffConfig;
use MonorepoBuilder20211104\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class MonorepoBuilderKernel extends \MonorepoBuilder20211104\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs($configFiles) : \MonorepoBuilder20211104\Psr\Container\ContainerInterface
    {
        $configFiles[] = __DIR__ . '/../../config/config.php';
        $configFiles[] = \MonorepoBuilder20211104\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonManipulatorConfig::FILE_PATH;
        $configFiles[] = \MonorepoBuilder20211104\Symplify\ConsoleColorDiff\ValueObject\ConsoleColorDiffConfig::FILE_PATH;
        return $this->create([], [], $configFiles);
    }
}
