<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection;

use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Exception\OutOfBoundsException;
/**
 * This definition extends another definition.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ChildDefinition extends Definition
{
    /**
     * @var string
     */
    private $parent;
    /**
     * @param string $parent The id of Definition instance to decorate
     */
    public function __construct(string $parent)
    {
        $this->parent = $parent;
    }
    /**
     * Returns the Definition to inherit from.
     */
    public function getParent() : string
    {
        return $this->parent;
    }
    /**
     * Sets the Definition to inherit from.
     *
     * @return $this
     */
    public function setParent(string $parent)
    {
        $this->parent = $parent;
        return $this;
    }
    /**
     * Gets an argument to pass to the service constructor/factory method.
     *
     * If replaceArgument() has been used to replace an argument, this method
     * will return the replacement value.
     *
     * @throws OutOfBoundsException When the argument does not exist
     * @param int|string $index
     * @return mixed
     */
    public function getArgument($index)
    {
        if (\array_key_exists('index_' . $index, $this->arguments)) {
            return $this->arguments['index_' . $index];
        }
        return parent::getArgument($index);
    }
    /**
     * You should always use this method when overwriting existing arguments
     * of the parent definition.
     *
     * If you directly call setArguments() keep in mind that you must follow
     * certain conventions when you want to overwrite the arguments of the
     * parent definition, otherwise your arguments will only be appended.
     *
     * @return $this
     *
     * @throws InvalidArgumentException when $index isn't an integer
     * @param int|string $index
     * @param mixed $value
     */
    public function replaceArgument($index, $value)
    {
        if (\is_int($index)) {
            $this->arguments['index_' . $index] = $value;
        } elseif (\strncmp($index, '$', \strlen('$')) === 0) {
            $this->arguments[$index] = $value;
        } else {
            throw new InvalidArgumentException('The argument must be an existing index or the name of a constructor\'s parameter.');
        }
        return $this;
    }
}
