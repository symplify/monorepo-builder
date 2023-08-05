<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\DependencyUpdater;

use Symplify\MonorepoBuilder\DependencyUpdater;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class DependencyUpdaterTest extends AbstractKernelTestCase
{
    private DependencyUpdater $dependencyUpdater;

    private SmartFileSystem $smartFileSystem;

    protected function setUp(): void
    {
        $this->bootKernel(MonorepoBuilderKernel::class);

        $this->dependencyUpdater = $this->getService(DependencyUpdater::class);
        $this->smartFileSystem = $this->getService(SmartFileSystem::class);
    }

    protected function tearDown(): void
    {
        $this->smartFileSystem->copy(__DIR__ . '/Source/backup-first.json', __DIR__ . '/Source/first.json');
    }

    public function testUpdateFileInfosWithVendorAndVersion(): void
    {
        $fileInfos = [new SmartFileInfo(__DIR__ . '/Source/first.json')];

        $this->dependencyUpdater->updateFileInfosWithVendorAndVersion($fileInfos, 'ex', '5.0-dev');

        $this->assertFileEquals(__DIR__ . '/Source/expected-first.json', __DIR__ . '/Source/first.json');
    }
}
