<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\ComposerJsonManipulator\Sorter;

use PHPUnit\Framework\Attributes\DataProvider;
use Iterator;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\Sorter\ComposerPackageSorter;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class ComposerPackageSorterTest extends AbstractKernelTestCase
{
    private ComposerPackageSorter $composerPackageSorter;

    protected function setUp(): void
    {
        $this->bootKernel(MonorepoBuilderKernel::class);
        $this->composerPackageSorter = $this->getService(ComposerPackageSorter::class);
    }

    /**
     * @param array<string, string> $packages
     * @param array<string, string> $expectedSortedPackages
     */
    #[DataProvider('provideData')]
    public function test(array $packages, array $expectedSortedPackages): void
    {
        $sortedPackages = $this->composerPackageSorter->sortPackages($packages);
        $this->assertSame($expectedSortedPackages, $sortedPackages);
    }

    /**
     * @return Iterator<array<int, array<string, string>>>
     */
    public static function provideData(): Iterator
    {
        yield [
            [
                'symfony/console' => '^5.2',
                'php' => '^8.0',
                'ext-json' => '*',
            ],
            [
                'php' => '^8.0',
                'ext-json' => '*',
                'symfony/console' => '^5.2',
            ],
        ];
    }
}
