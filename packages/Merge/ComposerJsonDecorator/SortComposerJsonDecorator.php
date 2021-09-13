<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20210913\Symplify\PackageBuilder\Parameter\ParameterProvider;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\SortComposerJsonDecorator\SortComposerJsonDecoratorTest
 */
final class SortComposerJsonDecorator implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @var string[]
     */
    private $sectionOrder = [];
    public function __construct(\MonorepoBuilder20210913\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->sectionOrder = $parameterProvider->provideArrayParameter(\Symplify\MonorepoBuilder\ValueObject\Option::SECTION_ORDER);
    }
    /**
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson
     */
    public function decorate($composerJson) : void
    {
        $orderedKeys = $composerJson->getOrderedKeys();
        \usort($orderedKeys, function (string $key1, string $key2) : int {
            return $this->findKeyPosition($key1) <=> $this->findKeyPosition($key2);
        });
        $composerJson->setOrderedKeys($orderedKeys);
    }
    /**
     * @return int|string|bool
     */
    private function findKeyPosition(string $key)
    {
        return \array_search($key, $this->sectionOrder, \true);
    }
}
