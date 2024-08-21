<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\ComposerKeyMerger;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\MonorepoBuilder\Merge\ComposerKeyMerger\MinimalStabilityKeyMerger;
use Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\AbstractComposerJsonDecorator;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @coversDefaultClass \Symplify\MonorepoBuilder\Merge\ComposerKeyMerger\MinimalStabilityKeyMerger
 */
final class MinimalStabilityKeyMergerTest extends AbstractComposerJsonDecorator
{
    #[DataProvider('provideData')]
    public function testFixture(SmartFileInfo $fixtureFileInfo): void
    {
        $trioContent = $this->trioFixtureSplitter->splitFileInfo($fixtureFileInfo);
        $mainComposerJson = $this->createComposerJson($trioContent->getFirstValue());
        $packageComposerJson = $this->createComposerJson($trioContent->getSecondValue());

        $minimalStabilityKeyMerger = new MinimalStabilityKeyMerger();
        $minimalStabilityKeyMerger->merge($mainComposerJson, $packageComposerJson);

        $this->assertComposerJsonEquals($trioContent->getExpectedResult(), $mainComposerJson);
    }

    public static function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectoryExclusively(__DIR__ . '/Fixture/MinimalStability', '*.test');
    }
}
