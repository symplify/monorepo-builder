<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Attribute;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class TaggedLocator
{
    /**
     * @var string
     */
    public $tag;
    /**
     * @var string|null
     */
    public $indexAttribute;
    /**
     * @var string|null
     */
    public $defaultIndexMethod;
    /**
     * @var string|null
     */
    public $defaultPriorityMethod;
    /**
     * @var string|mixed[]
     */
    public $exclude = [];
    /**
     * @param string|mixed[] $exclude
     */
    public function __construct(string $tag, ?string $indexAttribute = null, ?string $defaultIndexMethod = null, ?string $defaultPriorityMethod = null, $exclude = [])
    {
        $this->tag = $tag;
        $this->indexAttribute = $indexAttribute;
        $this->defaultIndexMethod = $defaultIndexMethod;
        $this->defaultPriorityMethod = $defaultPriorityMethod;
        $this->exclude = $exclude;
    }
}
