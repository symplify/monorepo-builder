<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symplify\MonorepoBuilder\Config\MBConfig;

/**
 * Manages default release workers based on user configuration:
 *
 * 1. If disableDefaultWorkers() was called, removes all services tagged with 'monorepo.default_worker'.
 * 2. If workers() was called (without disableDefaultWorkers()), removes default workers whose class
 *    overlaps with user-registered workers to prevent duplicates while preserving user-specified order.
 */
final readonly class RemoveDefaultWorkersCompilerPass implements CompilerPassInterface
{
    private const DEFAULT_WORKER_TAG = 'monorepo.default_worker';

    public function process(ContainerBuilder $containerBuilder): void
    {
        $taggedServices = $containerBuilder->findTaggedServiceIds(self::DEFAULT_WORKER_TAG);

        if (MBConfig::isDisableDefaultWorkers()) {
            // Remove all default workers
            foreach (array_keys($taggedServices) as $serviceId) {
                if ($containerBuilder->hasDefinition($serviceId)) {
                    $containerBuilder->removeDefinition($serviceId);
                }
            }

            return;
        }

        // Remove default workers whose class was also registered by the user
        // via workers(), to avoid duplicates
        $userWorkerClasses = MBConfig::getUserWorkerClasses();
        if ($userWorkerClasses === []) {
            return;
        }

        foreach (array_keys($taggedServices) as $serviceId) {
            if (! $containerBuilder->hasDefinition($serviceId)) {
                continue;
            }

            $class = $containerBuilder->getDefinition($serviceId)->getClass() ?? $serviceId;
            if (in_array($class, $userWorkerClasses, true)) {
                $containerBuilder->removeDefinition($serviceId);
            }
        }
    }
}
