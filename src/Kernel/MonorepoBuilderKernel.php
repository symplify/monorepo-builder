<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Kernel;

use MonorepoBuilder20211111\Psr\Container\ContainerInterface;
use MonorepoBuilder20211111\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonManipulatorConfig;
use MonorepoBuilder20211111\Symplify\ConsoleColorDiff\ValueObject\ConsoleColorDiffConfig;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20211111\Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use MonorepoBuilder20211111\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class MonorepoBuilderKernel extends \MonorepoBuilder20211111\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs($configFiles) : \MonorepoBuilder20211111\Psr\Container\ContainerInterface
    {
        $configFiles[] = __DIR__ . '/../../config/config.php';
        $configFiles[] = \MonorepoBuilder20211111\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonManipulatorConfig::FILE_PATH;
        $configFiles[] = \MonorepoBuilder20211111\Symplify\ConsoleColorDiff\ValueObject\ConsoleColorDiffConfig::FILE_PATH;
        $autowireInterfacesCompilerPass = new \MonorepoBuilder20211111\Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass([\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface::class]);
        $compilerPasses = [$autowireInterfacesCompilerPass];
        return $this->create([], $compilerPasses, $configFiles);
    }
}
