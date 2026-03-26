<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symplify\MonorepoBuilder\Config\MBConfig;

/**
 * Removes default release workers from the container if MBConfig::disableDefaultWorkers() was called.
 *
 * This uses a tag-based approach to distinguish between:
 * - Default workers registered by config/config.php (tagged with 'monorepo.default_worker')
 * - User-registered workers (no tag, or re-registered without the tag)
 *
 * When user calls disableDefaultWorkers() and then manually registers a worker
 * (even one with the same class as a default worker), only the tagged default
 * definition is removed, preserving the user's explicit registration.
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
