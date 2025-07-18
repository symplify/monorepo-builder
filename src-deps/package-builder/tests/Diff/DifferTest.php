<?php

declare(strict_types=1);

namespace Symplify\PackageBuilder\Tests\Diff;

use SebastianBergmann\Diff\Differ;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\PackageBuilder\Tests\HttpKernel\PackageBuilderTestKernel;
use Symplify\PackageBuilder\ValueObject\ConsoleColorDiffConfig;

final class DifferTest extends AbstractKernelTestCase
{
    public function test(): void
    {
        $this->bootKernelWithConfigs(PackageBuilderTestKernel::class, [
            ConsoleColorDiffConfig::FILE_PATH,
        ]);

        $differ = $this->getService(Differ::class);
        $this->assertInstanceOf(Differ::class, $differ);
    }
}
