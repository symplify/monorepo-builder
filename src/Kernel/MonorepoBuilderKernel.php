<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Kernel;

use Psr\Container\ContainerInterface;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
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
        $compilerPasses = [$autowireInterfacesCompilerPass];

        return $this->create($configFiles, $compilerPasses, []);
    }
}
