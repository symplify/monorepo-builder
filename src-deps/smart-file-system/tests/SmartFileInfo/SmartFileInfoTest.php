<?php

declare(strict_types=1);

namespace Symplify\SmartFileSystem\Tests\SmartFileInfo;

use PHPUnit\Framework\TestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class SmartFileInfoTest extends TestCase
{
    protected function setUp(): void
    {
        // prefer local autoloaded file
        if (! class_exists(SmartFileInfo::class)) {
            require_once __DIR__ . '/../../src/SmartFileInfo.php';
        }

        parent::setUp();
    }

    public function testRelatives(): void
    {
        $smartFileInfo = new SmartFileInfo(__FILE__);

        $this->assertNotSame($smartFileInfo->getRelativePath(), $smartFileInfo->getRealPath());

        $normalizedRelativePath = $this->normalizePath($smartFileInfo->getRelativePath());
        $normalizedDir = $this->normalizePath(__DIR__);
        $this->assertStringEndsWith($normalizedRelativePath, $normalizedDir);

        $normalizedRelativePathname = $this->normalizePath($smartFileInfo->getRelativePathname());
        $normalizeFile = $this->normalizePath(__FILE__);
        $this->assertStringEndsWith($normalizedRelativePathname, $normalizeFile);
    }

    public function testRealPathWithoutSuffix(): void
    {
        $smartFileInfo = new SmartFileInfo(__DIR__ . '/Source/AnotherFile.txt');

        $this->assertStringEndsWith(
            'tests/SmartFileInfo/Source/AnotherFile',
            $smartFileInfo->getRealPathWithoutSuffix()
        );
    }

    public function testRelativeToDir(): void
    {
        $smartFileInfo = new SmartFileInfo(__DIR__ . '/Source/AnotherFile.txt');

        $relativePath = $smartFileInfo->getRelativeFilePathFromDirectory(__DIR__);
        $this->assertSame('Source/AnotherFile.txt', $relativePath);
    }

    public function testDoesFnmatch(): void
    {
        $smartFileInfo = new SmartFileInfo(__DIR__ . '/Source/AnotherFile.txt');

        // Test param
        $this->assertStringEndsWith(
            $this->normalizePath('tests\\SmartFileInfo\\Source\\AnotherFile.txt'),
            $smartFileInfo->getRelativePathname()
        );
        $this->assertStringEndsWith(
            $this->normalizePath('tests/SmartFileInfo/Source/AnotherFile.txt'),
            $smartFileInfo->getRelativePathname()
        );

        // Test function
        $this->assertTrue($smartFileInfo->doesFnmatch(__DIR__ . '/Source/AnotherFile.txt'));
        $this->assertTrue($smartFileInfo->doesFnmatch(__DIR__ . '\\Source\\AnotherFile.txt'));
    }

    /**
     * Normalizing required to allow running tests on windows.
     */
    private function normalizePath(string $path): string
    {
        return str_replace('\\', '/', $path);
    }
}
