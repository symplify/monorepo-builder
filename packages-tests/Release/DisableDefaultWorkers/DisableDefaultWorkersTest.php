<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Release\DisableDefaultWorkers;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;

/**
 * Tests for MBConfig::disableDefaultWorkers() functionality.
 *
 * @see https://github.com/symplify/monorepo-builder/issues/95
 */
final class DisableDefaultWorkersTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset static state before each test
        $this->resetMBConfigState();
    }

    protected function tearDown(): void
    {
        // Clean up static state after each test
        $this->resetMBConfigState();
    }

    /**
     * Scenario 1: Default behavior - workers should be registered
     */
    public function testDefaultWorkersAreRegisteredByDefault(): void
    {
        $monorepoBuilderKernel = new MonorepoBuilderKernel();
        $container = $monorepoBuilderKernel->createFromConfigs([__DIR__ . '/config/default_behavior.php']);

        $this->assertTrue($container->has(TagVersionReleaseWorker::class));
        $this->assertTrue($container->has(PushTagReleaseWorker::class));
    }

    /**
     * Scenario 2: disableDefaultWorkers() removes both default workers
     */
    public function testDisableDefaultWorkersRemovesBothWorkers(): void
    {
        $monorepoBuilderKernel = new MonorepoBuilderKernel();
        $container = $monorepoBuilderKernel->createFromConfigs([__DIR__ . '/config/disable_default_workers.php']);

        $this->assertFalse($container->has(TagVersionReleaseWorker::class));
        $this->assertFalse($container->has(PushTagReleaseWorker::class));
    }

    /**
     * Scenario 3: disableDefaultWorkers() + manually add worker preserves user's worker
     */
    public function testDisableDefaultWorkersPreservesUserAddedWorker(): void
    {
        $monorepoBuilderKernel = new MonorepoBuilderKernel();
        $container = $monorepoBuilderKernel->createFromConfigs([__DIR__ . '/config/disable_and_add_custom.php']);

        // User explicitly added TagVersionReleaseWorker, so it should be preserved
        $this->assertTrue($container->has(TagVersionReleaseWorker::class));
        // User did not add PushTagReleaseWorker, so it should be removed
        $this->assertFalse($container->has(PushTagReleaseWorker::class));
    }

    private function resetMBConfigState(): void
    {
        $reflectionClass = new ReflectionClass(MBConfig::class);
        $reflectionProperty = $reflectionClass->getProperty('disableDefaultWorkers');
        $reflectionProperty->setValue(null, false);
    }
}
