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
        if ($this->sectionOrder === []) {
            return;
        }

        $orderedKeys = $composerJson->getJsonKeys();
        $knownKeys = [];
        $unknownKeys = [];

        foreach ($orderedKeys as $key) {
            if (in_array($key, $this->sectionOrder, true)) {
                $knownKeys[] = $key;
            } else {
                $unknownKeys[] = $key;
            }
        }

        usort(
            $knownKeys,
            fn (string $a, string $b): int => array_search($a, $this->sectionOrder, true) <=> array_search($b, $this->sectionOrder, true)
        );

        $composerJson->setOrderedKeys(array_merge($knownKeys, $unknownKeys));
    }
}
