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
            // no custom order configured — preserve original file key order
            $composerJson->setOrderedKeys($composerJson->getJsonKeys());
            return;
        }

        $orderedKeys = $composerJson->getJsonKeys();
        $knownKeys = [];
        $unknownKeys = [];

        foreach ($orderedKeys as $orderedKey) {
            if (in_array($orderedKey, $this->sectionOrder, true)) {
                $knownKeys[] = $orderedKey;
            } else {
                $unknownKeys[] = $orderedKey;
            }
        }

        usort(
            $knownKeys,
            fn (string $a, string $b): int => array_search($a, $this->sectionOrder, true) <=> array_search($b, $this->sectionOrder, true)
        );

        $composerJson->setOrderedKeys(array_merge($knownKeys, $unknownKeys));
    }
}
