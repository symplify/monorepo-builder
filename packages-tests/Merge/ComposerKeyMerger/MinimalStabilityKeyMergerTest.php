<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\ComposerKeyMerger;

use PHPUnit\Framework\Attributes\DataProvider;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\MonorepoBuilder\Merge\ComposerKeyMerger\MinimalStabilityKeyMerger;
use Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\AbstractComposerJsonDecoratorTest;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @coversDefaultClass \Symplify\MonorepoBuilder\Merge\ComposerKeyMerger\MinimalStabilityKeyMerger
 */
final class MinimalStabilityKeyMergerTest extends AbstractComposerJsonDecoratorTest
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

    public static function provideData(): iterable
    {
        return StaticFixtureFinder::yieldDirectoryExclusively(__DIR__ . '/Fixture/MinimalStability', '*.json');
    }
}
