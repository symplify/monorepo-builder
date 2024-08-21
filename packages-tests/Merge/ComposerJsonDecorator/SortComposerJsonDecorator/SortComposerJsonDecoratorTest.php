<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\SortComposerJsonDecorator;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ComposerJsonFactory;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\SortComposerJsonDecorator;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class SortComposerJsonDecoratorTest extends AbstractKernelTestCase
{
    private ComposerJson $composerJson;

    private SortComposerJsonDecorator $sortComposerJsonDecorator;

    protected function setUp(): void
    {
        $this->bootKernel(MonorepoBuilderKernel::class);

        $this->composerJson = $this->createComposerJson();
        $this->sortComposerJsonDecorator = $this->getService(SortComposerJsonDecorator::class);
    }

    public function test(): void
    {
        $this->sortComposerJsonDecorator->decorate($this->composerJson);

        $this->assertSame(
            [
                'random-this',
                'random-that',
                ComposerJsonSection::REQUIRE,
                ComposerJsonSection::REQUIRE_DEV,
                ComposerJsonSection::AUTOLOAD,
                ComposerJsonSection::AUTOLOAD_DEV,
            ],
            $this->composerJson->getOrderedKeys()
        );
    }

    private function createComposerJson(): ComposerJson
    {
        /** @var ComposerJsonFactory $composerJsonFactory */
        $composerJsonFactory = $this->getService(ComposerJsonFactory::class);

        return $composerJsonFactory->createFromArray([
            'random-this' => ['foo'],
            ComposerJsonSection::AUTOLOAD_DEV => ['foo'],
            ComposerJsonSection::AUTOLOAD => ['bar'],
            'random-that' => ['bar'],
            ComposerJsonSection::REQUIRE_DEV => ['bar'],
            ComposerJsonSection::REQUIRE => ['foo'],
        ]);
    }
}
