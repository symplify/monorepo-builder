<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Argument;

/**
 * Represents a collection of services found by tag name to lazily iterate over.
 *
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
class TaggedIteratorArgument extends IteratorArgument
{
    /**
     * @var string
     */
    private $tag;
    /**
     * @var mixed
     */
    private $indexAttribute;
    /**
     * @var string|null
     */
    private $defaultIndexMethod;
    /**
     * @var string|null
     */
    private $defaultPriorityMethod;
    /**
     * @var bool
     */
    private $needsIndexes;
    /**
     * @var mixed[]
     */
    private $exclude;
    /**
     * @param string      $tag                   The name of the tag identifying the target services
     * @param string|null $indexAttribute        The name of the attribute that defines the key referencing each service in the tagged collection
     * @param string|null $defaultIndexMethod    The static method that should be called to get each service's key when their tag doesn't define the previous attribute
     * @param bool        $needsIndexes          Whether indexes are required and should be generated when computing the map
     * @param string|null $defaultPriorityMethod The static method that should be called to get each service's priority when their tag doesn't define the "priority" attribute
     * @param array       $exclude               Services to exclude from the iterator
     */
    public function __construct(string $tag, string $indexAttribute = null, string $defaultIndexMethod = null, bool $needsIndexes = \false, string $defaultPriorityMethod = null, array $exclude = [])
    {
        parent::__construct([]);
        if (null === $indexAttribute && $needsIndexes) {
            $indexAttribute = \preg_match('/[^.]++$/', $tag, $m) ? $m[0] : $tag;
        }
        $this->tag = $tag;
        $this->indexAttribute = $indexAttribute;
        $this->defaultIndexMethod = $defaultIndexMethod ?: ($indexAttribute ? 'getDefault' . \str_replace(' ', '', \ucwords(\preg_replace('/[^a-zA-Z0-9\\x7f-\\xff]++/', ' ', $indexAttribute))) . 'Name' : null);
        $this->needsIndexes = $needsIndexes;
        $this->defaultPriorityMethod = $defaultPriorityMethod ?: ($indexAttribute ? 'getDefault' . \str_replace(' ', '', \ucwords(\preg_replace('/[^a-zA-Z0-9\\x7f-\\xff]++/', ' ', $indexAttribute))) . 'Priority' : null);
        $this->exclude = $exclude;
    }
    public function getTag()
    {
        return $this->tag;
    }
    public function getIndexAttribute() : ?string
    {
        return $this->indexAttribute;
    }
    public function getDefaultIndexMethod() : ?string
    {
        return $this->defaultIndexMethod;
    }
    public function needsIndexes() : bool
    {
        return $this->needsIndexes;
    }
    public function getDefaultPriorityMethod() : ?string
    {
        return $this->defaultPriorityMethod;
    }
    public function getExclude() : array
    {
        return $this->exclude;
    }
}
