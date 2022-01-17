<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace MonorepoBuilder20220117\Nette\Neon\Node;

use MonorepoBuilder20220117\Nette\Neon;
use MonorepoBuilder20220117\Nette\Neon\Node;
/** @internal */
final class EntityChainNode extends \MonorepoBuilder20220117\Nette\Neon\Node
{
    /** @var EntityNode[] */
    public $chain = [];
    public function __construct(array $chain = [], int $startPos = null, int $endPos = null)
    {
        $this->chain = $chain;
        $this->startPos = $startPos;
        $this->endPos = $endPos ?? $startPos;
    }
    public function toValue() : \MonorepoBuilder20220117\Nette\Neon\Entity
    {
        $entities = [];
        foreach ($this->chain as $item) {
            $entities[] = $item->toValue();
        }
        return new \MonorepoBuilder20220117\Nette\Neon\Entity(\MonorepoBuilder20220117\Nette\Neon\Neon::CHAIN, $entities);
    }
    public function toString() : string
    {
        return \implode('', \array_map(function ($entity) {
            return $entity->toString();
        }, $this->chain));
    }
    public function getSubNodes() : array
    {
        $res = [];
        foreach ($this->chain as &$item) {
            $res[] =& $item;
        }
        return $res;
    }
}
