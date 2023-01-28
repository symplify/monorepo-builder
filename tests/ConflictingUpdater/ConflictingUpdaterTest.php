<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\ConflictingUpdater;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\ConflictingUpdater;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileSystem;

final class ConflictingUpdaterTest extends AbstractKernelTestCase
{
    private ConflictingUpdater $conflictingUpdater;

    private SmartFileSystem $smartFileSystem;

    protected function setUp(): void
    {
        $this->bootKernel(MonorepoBuilderKernel::class);

        $this->conflictingUpdater = $this->getService(ConflictingUpdater::class);
        $this->smartFileSystem = $this->getService(SmartFileSystem::class);
    }

    public function test(): void
    {
        // prepare input file
        $this->smartFileSystem->copy(
            __DIR__ . '/Fixture/input_composer_backup.json',
            __DIR__ . '/Fixture/input_composer.json'
        );

        $packageComposerFilePaths = [__DIR__ . '/Fixture/input_composer.json'];

        $this->conflictingUpdater->updateFilePathsWithVendorAndVersion(
            $packageComposerFilePaths,
            ['symplify/another-package', 'symplify/package-builder'],
            new Version('9.2')
        );

        $this->assertJsonFileEqualsJsonFile(
            __DIR__ . '/Fixture/expected_composer.json',
            __DIR__ . '/Fixture/input_composer.json'
        );

        // remove temp file
        $this->smartFileSystem->remove([__DIR__ . '/Fixture/input_composer.json']);
    }
}
