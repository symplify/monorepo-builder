<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\SortComposerJsonDecorator\SortComposerJsonDecoratorTest
 */
final class SortComposerJsonDecorator implements ComposerJsonDecoratorInterface
{
    /**
     * @var string[]
     */
    private array $sectionOrder = [];

    public function __construct(ParameterProvider $parameterProvider)
    {
        $this->sectionOrder = $parameterProvider->provideArrayParameter(Option::SECTION_ORDER);
    }

    public function decorate(ComposerJson $composerJson): void
    {
        $orderedKeys = $composerJson->getJsonKeys();

        usort(
            $orderedKeys,
            fn (string $key1, string $key2): int => $this->findKeyPosition($key1) <=> $this->findKeyPosition($key2)
        );

        $composerJson->setOrderedKeys($orderedKeys);
    }

    private function findKeyPosition(string $key): int | string | bool
    {
        return array_search($key, $this->sectionOrder, true);
    }
}
