<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider;
/**
 * @see \Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\SortComposerJsonDecorator\SortComposerJsonDecoratorTest
 */
final class SortComposerJsonDecorator implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerJsonDecoratorInterface
{
    /**
     * @var string[]
     */
    private $sectionOrder = [];
    public function __construct(\MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->sectionOrder = $parameterProvider->provideArrayParameter(\Symplify\MonorepoBuilder\ValueObject\Option::SECTION_ORDER);
    }
    public function decorate(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson) : void
    {
        $orderedKeys = $composerJson->getOrderedKeys();
        \usort($orderedKeys, function (string $key1, string $key2) : int {
            return $this->findKeyPosition($key1) <=> $this->findKeyPosition($key2);
        });
        $composerJson->setOrderedKeys($orderedKeys);
    }
    /**
     * @return bool|int|string
     */
    private function findKeyPosition(string $key)
    {
        return \array_search($key, $this->sectionOrder, \true);
    }
}
