<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace MonorepoBuilder20220525\Nette\Neon\Node;

use MonorepoBuilder20220525\Nette\Neon;
use MonorepoBuilder20220525\Nette\Neon\Node;
/** @internal */
final class EntityChainNode extends \MonorepoBuilder20220525\Nette\Neon\Node
{
    /** @var EntityNode[] */
    public $chain = [];
    public function __construct(array $chain = [])
    {
        $this->chain = $chain;
    }
    public function toValue() : \MonorepoBuilder20220525\Nette\Neon\Entity
    {
        $entities = [];
        foreach ($this->chain as $item) {
            $entities[] = $item->toValue();
        }
        return new \MonorepoBuilder20220525\Nette\Neon\Entity(\MonorepoBuilder20220525\Nette\Neon\Neon::Chain, $entities);
    }
    public function toString() : string
    {
        return \implode('', \array_map(function ($entity) {
            return $entity->toString();
        }, $this->chain));
    }
    public function &getIterator() : \Generator
    {
        foreach ($this->chain as &$item) {
            (yield $item);
        }
    }
}
