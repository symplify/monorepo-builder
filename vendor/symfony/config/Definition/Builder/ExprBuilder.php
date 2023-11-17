<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\Config\Definition\Builder;

use MonorepoBuilderPrefix202311\Symfony\Component\Config\Definition\Exception\UnsetKeyException;
/**
 * This class builds an if expression.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class ExprBuilder
{
    public const TYPE_ANY = 'any';
    public const TYPE_STRING = 'string';
    public const TYPE_NULL = 'null';
    public const TYPE_ARRAY = 'array';
    protected $node;
    public $allowedTypes;
    public $ifPart;
    public $thenPart;
    public function __construct(NodeDefinition $node)
    {
        $this->node = $node;
    }
    /**
     * Marks the expression as being always used.
     *
     * @return $this
     */
    public function always(\Closure $then = null)
    {
        $this->ifPart = static function () {
            return \true;
        };
        $this->allowedTypes = self::TYPE_ANY;
        if (null !== $then) {
            $this->thenPart = $then;
        }
        return $this;
    }
    /**
     * Sets a closure to use as tests.
     *
     * The default one tests if the value is true.
     *
     * @return $this
     */
    public function ifTrue(\Closure $closure = null)
    {
        $this->ifPart = $closure ?? static function ($v) {
            return \true === $v;
        };
        $this->allowedTypes = self::TYPE_ANY;
        return $this;
    }
    /**
     * Tests if the value is a string.
     *
     * @return $this
     */
    public function ifString()
    {
        $this->ifPart = \Closure::fromCallable('is_string');
        $this->allowedTypes = self::TYPE_STRING;
        return $this;
    }
    /**
     * Tests if the value is null.
     *
     * @return $this
     */
    public function ifNull()
    {
        $this->ifPart = \Closure::fromCallable('is_null');
        $this->allowedTypes = self::TYPE_NULL;
        return $this;
    }
    /**
     * Tests if the value is empty.
     *
     * @return $this
     */
    public function ifEmpty()
    {
        $this->ifPart = static function ($v) {
            return empty($v);
        };
        $this->allowedTypes = self::TYPE_ANY;
        return $this;
    }
    /**
     * Tests if the value is an array.
     *
     * @return $this
     */
    public function ifArray()
    {
        $this->ifPart = \Closure::fromCallable('is_array');
        $this->allowedTypes = self::TYPE_ARRAY;
        return $this;
    }
    /**
     * Tests if the value is in an array.
     *
     * @return $this
     */
    public function ifInArray(array $array)
    {
        $this->ifPart = static function ($v) use($array) {
            return \in_array($v, $array, \true);
        };
        $this->allowedTypes = self::TYPE_ANY;
        return $this;
    }
    /**
     * Tests if the value is not in an array.
     *
     * @return $this
     */
    public function ifNotInArray(array $array)
    {
        $this->ifPart = static function ($v) use($array) {
            return !\in_array($v, $array, \true);
        };
        $this->allowedTypes = self::TYPE_ANY;
        return $this;
    }
    /**
     * Transforms variables of any type into an array.
     *
     * @return $this
     */
    public function castToArray()
    {
        $this->ifPart = static function ($v) {
            return !\is_array($v);
        };
        $this->allowedTypes = self::TYPE_ANY;
        $this->thenPart = static function ($v) {
            return [$v];
        };
        return $this;
    }
    /**
     * Sets the closure to run if the test pass.
     *
     * @return $this
     */
    public function then(\Closure $closure)
    {
        $this->thenPart = $closure;
        return $this;
    }
    /**
     * Sets a closure returning an empty array.
     *
     * @return $this
     */
    public function thenEmptyArray()
    {
        $this->thenPart = static function () {
            return [];
        };
        return $this;
    }
    /**
     * Sets a closure marking the value as invalid at processing time.
     *
     * if you want to add the value of the node in your message just use a %s placeholder.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function thenInvalid(string $message)
    {
        $this->thenPart = static function ($v) use($message) {
            throw new \InvalidArgumentException(\sprintf($message, \json_encode($v)));
        };
        return $this;
    }
    /**
     * Sets a closure unsetting this key of the array at processing time.
     *
     * @return $this
     *
     * @throws UnsetKeyException
     */
    public function thenUnset()
    {
        $this->thenPart = static function () {
            throw new UnsetKeyException('Unsetting key.');
        };
        return $this;
    }
    /**
     * Returns the related node.
     *
     * @throws \RuntimeException
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition|\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\VariableNodeDefinition
     */
    public function end()
    {
        if (null === $this->ifPart) {
            throw new \RuntimeException('You must specify an if part.');
        }
        if (null === $this->thenPart) {
            throw new \RuntimeException('You must specify a then part.');
        }
        return $this->node;
    }
    /**
     * Builds the expressions.
     *
     * @param ExprBuilder[] $expressions An array of ExprBuilder instances to build
     */
    public static function buildExpressions(array $expressions) : array
    {
        foreach ($expressions as $k => $expr) {
            if ($expr instanceof self) {
                $if = $expr->ifPart;
                $then = $expr->thenPart;
                $expressions[$k] = static function ($v) use($if, $then) {
                    return $if($v) ? $then($v) : $v;
                };
            }
        }
        return $expressions;
    }
}
