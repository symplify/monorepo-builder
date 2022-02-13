<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20220213\Symfony\Component\Config\Definition\Builder;

use MonorepoBuilder20220213\Symfony\Component\Config\Definition\FloatNode;
/**
 * This class provides a fluent interface for defining a float node.
 *
 * @author Jeanmonod David <david.jeanmonod@gmail.com>
 */
class FloatNodeDefinition extends \MonorepoBuilder20220213\Symfony\Component\Config\Definition\Builder\NumericNodeDefinition
{
    /**
     * Instantiates a Node.
     */
    protected function instantiateNode() : \MonorepoBuilder20220213\Symfony\Component\Config\Definition\ScalarNode
    {
        return new \MonorepoBuilder20220213\Symfony\Component\Config\Definition\FloatNode($this->name, $this->parent, $this->min, $this->max, $this->pathSeparator);
    }
}
