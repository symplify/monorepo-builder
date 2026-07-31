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

    /**
     * The output must stay the same on every supported sebastian/diff version,
     * as 9.0 swaps the underlying output builder.
     */
    public function testOutputFormatIsStableAcrossDiffVersions(): void
    {
        $this->bootKernelWithConfigs(PackageBuilderTestKernel::class, [
            ConsoleColorDiffConfig::FILE_PATH,
        ]);

        $differ = $this->getService(Differ::class);

        $expectedDiff = <<<'DIFF'
@@ @@
 first
-second
+changed
 third

DIFF;

        $this->assertSame($expectedDiff, $differ->diff(
            "first\nsecond\nthird\n",
            "first\nchanged\nthird\n"
        ));
    }

    /**
     * Context is large enough that distant changes stay in a single hunk,
     * so the whole file is always shown.
     */
    public function testDistantChangesStayInSingleHunk(): void
    {
        $this->bootKernelWithConfigs(PackageBuilderTestKernel::class, [
            ConsoleColorDiffConfig::FILE_PATH,
        ]);

        $differ = $this->getService(Differ::class);

        $oldLines = range(1, 40);
        $newLines = $oldLines;
        $newLines[0] = 'changed first';
        $newLines[39] = 'changed last';

        $diff = $differ->diff(
            implode("\n", $oldLines) . "\n",
            implode("\n", $newLines) . "\n"
        );

        $this->assertSame(1, substr_count($diff, '@@ @@'));
        $this->assertStringContainsString("\n 20\n", $diff);
    }
}
