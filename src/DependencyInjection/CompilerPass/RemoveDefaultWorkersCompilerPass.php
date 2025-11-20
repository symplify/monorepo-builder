<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;

/**
 * Removes default release workers from the container if MBConfig::disableDefaultWorkers() was called.
 * This needs to happen in a compiler pass rather than during config loading because:
 * 1. Default config is loaded before user config (to allow parameter overriding)
 * 2. User config calls disableDefaultWorkers() after default config has already registered workers
 * 3. Compiler passes run after all configs are loaded, so they can see the final state
 */
final readonly class RemoveDefaultWorkersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        // Check if user disabled default workers in their config
        if (! MBConfig::isDisableDefaultWorkers()) {
            return;
        }

        // Remove the default workers from the container
        $defaultWorkers = [
            TagVersionReleaseWorker::class,
            PushTagReleaseWorker::class,
        ];

        foreach ($defaultWorkers as $defaultWorker) {
            if ($containerBuilder->hasDefinition($defaultWorker)) {
                $containerBuilder->removeDefinition($defaultWorker);
            }
        }
    }
}
