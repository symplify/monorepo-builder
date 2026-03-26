<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Release\DisableDefaultWorkers;

use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\AddTagToChangelogReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider;
use Symplify\MonorepoBuilder\Release\ValueObject\Stage;
use Symplify\MonorepoBuilder\Tests\Release\DisableDefaultWorkers\Fixture\FetchTagsReleaseWorker;
use Symplify\MonorepoBuilder\Tests\Release\DisableDefaultWorkers\Fixture\GenerateChangelogReleaseWorker;

/**
 * Tests for MBConfig::disableDefaultWorkers() functionality.
 *
 * @see https://github.com/symplify/monorepo-builder/issues/95
 */
final class DisableDefaultWorkersTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetMBConfigState();
    }

    protected function tearDown(): void
    {
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

        // User did not add PushTagReleaseWorker, so it should be removed
        $this->assertFalse($container->has(PushTagReleaseWorker::class));

        // User explicitly added TagVersionReleaseWorker via workers(), so it should be available
        /** @var ReleaseWorkerProvider $provider */
        $provider = $container->get(ReleaseWorkerProvider::class);
        $workers = $provider->provideByStage(Stage::MAIN);
        $workerClasses = array_map(static fn (ReleaseWorkerInterface $releaseWorker): string => $releaseWorker::class, $workers);
        $this->assertContains(TagVersionReleaseWorker::class, $workerClasses);
    }

    /**
     * Scenario 4: workers() without disableDefaultWorkers() should not duplicate overlapping workers
     */
    public function testWorkersWithoutDisableDoesNotDuplicate(): void
    {
        $monorepoBuilderKernel = new MonorepoBuilderKernel();
        $container = $monorepoBuilderKernel->createFromConfigs([__DIR__ . '/config/add_custom_without_disable.php']);

        /** @var ReleaseWorkerProvider $provider */
        $provider = $container->get(ReleaseWorkerProvider::class);
        $workers = $provider->provideByStage(Stage::MAIN);
        $workerClasses = array_map(static fn (ReleaseWorkerInterface $releaseWorker): string => $releaseWorker::class, $workers);

        // Should have exactly 3 workers, no duplicates
        $this->assertSame([
            AddTagToChangelogReleaseWorker::class,
            TagVersionReleaseWorker::class,
            PushTagReleaseWorker::class,
        ], $workerClasses);
    }

    /**
     * Scenario 5: Reproduces the exact scenario from issue #111.
     * User wants custom workers to run BEFORE the default tag/push workers.
     *
     * @see https://github.com/symplify/monorepo-builder/issues/111
     */
    public function testWorkerOrderIsRespected(): void
    {
        $monorepoBuilderKernel = new MonorepoBuilderKernel();
        $container = $monorepoBuilderKernel->createFromConfigs([__DIR__ . '/config/disable_and_reorder.php']);

        /** @var ReleaseWorkerProvider $provider */
        $provider = $container->get(ReleaseWorkerProvider::class);
        $workers = $provider->provideByStage(Stage::MAIN);
        $workerClasses = array_map(static fn (ReleaseWorkerInterface $releaseWorker): string => $releaseWorker::class, $workers);

        $this->assertSame([
            FetchTagsReleaseWorker::class,
            GenerateChangelogReleaseWorker::class,
            TagVersionReleaseWorker::class,
            PushTagReleaseWorker::class,
        ], $workerClasses);
    }

    private function resetMBConfigState(): void
    {
        $reflectionClass = new ReflectionClass(MBConfig::class);

        $reflectionProperty = $reflectionClass->getProperty('disableDefaultWorkers');
        $reflectionProperty->setValue(null, false);

        $reflectionProperty = $reflectionClass->getProperty('userWorkerClasses');
        $reflectionProperty->setValue(null, []);
    }
}
