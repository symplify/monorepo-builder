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
        // Check if user disabled default workers in their config
        if (! MBConfig::isDisableDefaultWorkers()) {
            return;
        }

        // Find and remove only services tagged as default workers
        $taggedServices = $containerBuilder->findTaggedServiceIds(self::DEFAULT_WORKER_TAG);

        foreach (array_keys($taggedServices) as $serviceId) {
            if ($containerBuilder->hasDefinition($serviceId)) {
                $containerBuilder->removeDefinition($serviceId);
            }
        }
    }
}
