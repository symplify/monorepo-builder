<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Propagate\VersionPropagator;

use PHPUnit\Framework\Attributes\DataProvider;
use Iterator;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\MonorepoBuilder\Propagate\VersionPropagator;
use Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\AbstractComposerJsonDecorator;
use Symplify\SmartFileSystem\SmartFileInfo;

final class VersionPropagater extends AbstractComposerJsonDecorator
{
    private VersionPropagator $versionPropagator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->versionPropagator = $this->getService(VersionPropagator::class);
    }

    #[DataProvider('provideData')]
    public function test(SmartFileInfo $fixtureFileInfo): void
    {
        $trioContent = $this->trioFixtureSplitter->splitFileInfo($fixtureFileInfo);

        $mainComposerJson = $this->composerJsonFactory->createFromString($trioContent->getFirstValue());
        $packageComposerJson = $this->createComposerJson($trioContent->getSecondValue());

        $this->versionPropagator->propagate($mainComposerJson, $packageComposerJson);

        $this->assertComposerJsonEquals($trioContent->getExpectedResult(), $packageComposerJson);
    }

    public static function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectoryExclusively(__DIR__ . '/Fixture', '*.test');
    }
}
