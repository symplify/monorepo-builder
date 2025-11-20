<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Kernel;

use Psr\Container\ContainerInterface;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\DependencyInjection\CompilerPass\RemoveDefaultWorkersCompilerPass;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use Symplify\PackageBuilder\ValueObject\ConsoleColorDiffConfig;
use Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;

final class MonorepoBuilderKernel extends AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        // Add default config BEFORE user configs so user configs can override defaults
        array_unshift($configFiles, __DIR__ . '/../../config/config.php');
        $configFiles[] = ConsoleColorDiffConfig::FILE_PATH;

        $autowireInterfacesCompilerPass = new AutowireInterfacesCompilerPass([
            ReleaseWorkerInterface::class,
            TagResolverInterface::class,
        ]);

        // This compiler pass must run after all configs are loaded
        // to properly detect if user called disableDefaultWorkers()
        $removeDefaultWorkersCompilerPass = new RemoveDefaultWorkersCompilerPass();

        $compilerPasses = [
            $autowireInterfacesCompilerPass,
            $removeDefaultWorkersCompilerPass,
        ];

        return $this->create($configFiles, $compilerPasses, []);
    }
}
