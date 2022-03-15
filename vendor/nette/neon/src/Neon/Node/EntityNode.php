<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace MonorepoBuilder20220315\Nette\Neon\Node;

use MonorepoBuilder20220315\Nette\Neon\Entity;
use MonorepoBuilder20220315\Nette\Neon\Node;
/** @internal */
final class EntityNode extends \MonorepoBuilder20220315\Nette\Neon\Node
{
    /** @var Node */
    public $value;
    /** @var ArrayItemNode[] */
    public $attributes;
    public function __construct(\MonorepoBuilder20220315\Nette\Neon\Node $value, array $attributes = [])
    {
        $this->value = $value;
        $this->attributes = $attributes;
    }
    public function toValue() : \MonorepoBuilder20220315\Nette\Neon\Entity
    {
        return new \MonorepoBuilder20220315\Nette\Neon\Entity($this->value->toValue(), \MonorepoBuilder20220315\Nette\Neon\Node\ArrayItemNode::itemsToArray($this->attributes));
    }
    public function toString() : string
    {
        return $this->value->toString() . '(' . ($this->attributes ? \MonorepoBuilder20220315\Nette\Neon\Node\ArrayItemNode::itemsToInlineString($this->attributes) : '') . ')';
    }
    public function &getIterator() : \Generator
    {
        (yield $this->value);
        foreach ($this->attributes as &$item) {
            (yield $item);
        }
    }
}
