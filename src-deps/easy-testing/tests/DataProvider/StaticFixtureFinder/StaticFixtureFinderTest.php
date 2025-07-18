<?php

declare(strict_types=1);

namespace Symplify\EasyTesting\Tests\DataProvider\StaticFixtureFinder;

use PHPUnit\Framework\TestCase;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class StaticFixtureFinderTest extends TestCase
{
    public function testYieldDirectory(): void
    {
        $files = StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.php');
        $files = iterator_to_array($files);
        $this->assertCount(2, $files);
    }

    public function testYieldDirectoryThrowException(): void
    {
        $files = StaticFixtureFinder::yieldDirectory(__DIR__ . '/FixtureMulti', '*.php');
        $files = iterator_to_array($files);
        $this->assertCount(1, $files);
    }

    public function testYieldDirectoryWithRelativePathname(): void
    {
        $files = StaticFixtureFinder::yieldDirectoryWithRelativePathname(__DIR__ . '/Fixture', '*.php');
        $files = iterator_to_array($files);
        $this->assertCount(2, $files);
        $this->assertArrayHasKey('foo.php', $files);
        $this->assertArrayHasKey('bar.php', $files);
    }

    public function testYieldDirectoryWithRelativePathnameThrowException(): void
    {
        $files = StaticFixtureFinder::yieldDirectoryWithRelativePathname(__DIR__ . '/FixtureMulti', '*.php');
        $files = iterator_to_array($files);
        $this->assertCount(1, $files);
    }

    public function testYieldDirectoryExclusivelyThrowException(): void
    {
        $this->expectException(ShouldNotHappenException::class);

        StaticFixtureFinder::yieldDirectoryExclusively(__DIR__ . '/FixtureMulti', '*.php');
    }
}
