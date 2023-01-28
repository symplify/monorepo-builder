<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Kernel;

use MonorepoBuilderPrefix202301\Psr\Container\ContainerInterface;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilderPrefix202301\Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use MonorepoBuilderPrefix202301\Symplify\PackageBuilder\ValueObject\ConsoleColorDiffConfig;
use MonorepoBuilderPrefix202301\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class MonorepoBuilderKernel extends AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles) : ContainerInterface
    {
        $configFiles[] = __DIR__ . '/../../config/config.php';
        $configFiles[] = ConsoleColorDiffConfig::FILE_PATH;
        $autowireInterfacesCompilerPass = new AutowireInterfacesCompilerPass([ReleaseWorkerInterface::class]);
        $compilerPasses = [$autowireInterfacesCompilerPass];
        return $this->create($configFiles, $compilerPasses, []);
    }
}
