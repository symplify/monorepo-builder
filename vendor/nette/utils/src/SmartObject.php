<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace MonorepoBuilder20211223\Nette;

use MonorepoBuilder20211223\Nette\Utils\ObjectHelpers;
/**
 * Strict class for better experience.
 * - 'did you mean' hints
 * - access to undeclared members throws exceptions
 * - support for @property annotations
 * - support for calling event handlers stored in $onEvent via onEvent()
 */
trait SmartObject
{
    /**
     * @throws MemberAccessException
     */
    public function __call(string $name, array $args)
    {
        $class = static::class;
        if (\MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::hasProperty($class, $name) === 'event') {
            // calling event handlers
            $handlers = $this->{$name} ?? null;
            if (\is_iterable($handlers)) {
                foreach ($handlers as $handler) {
                    $handler(...$args);
                }
            } elseif ($handlers !== null) {
                throw new \MonorepoBuilder20211223\Nette\UnexpectedValueException("Property {$class}::\${$name} must be iterable or null, " . \gettype($handlers) . ' given.');
            }
        } else {
            \MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::strictCall($class, $name);
        }
    }
    /**
     * @throws MemberAccessException
     */
    public static function __callStatic(string $name, array $args)
    {
        \MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::strictStaticCall(static::class, $name);
    }
    /**
     * @return mixed
     * @throws MemberAccessException if the property is not defined.
     */
    public function &__get(string $name)
    {
        $class = static::class;
        if ($prop = \MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::getMagicProperties($class)[$name] ?? null) {
            // property getter
            if (!($prop & 0b1)) {
                throw new \MonorepoBuilder20211223\Nette\MemberAccessException("Cannot read a write-only property {$class}::\${$name}.");
            }
            $m = ($prop & 0b10 ? 'get' : 'is') . $name;
            if ($prop & 0b100) {
                // return by reference
                return $this->{$m}();
            } else {
                $val = $this->{$m}();
                return $val;
            }
        } else {
            \MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::strictGet($class, $name);
        }
    }
    /**
     * @param  mixed  $value
     * @return void
     * @throws MemberAccessException if the property is not defined or is read-only
     */
    public function __set(string $name, $value)
    {
        $class = static::class;
        if (\MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::hasProperty($class, $name)) {
            // unsetted property
            $this->{$name} = $value;
        } elseif ($prop = \MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::getMagicProperties($class)[$name] ?? null) {
            // property setter
            if (!($prop & 0b1000)) {
                throw new \MonorepoBuilder20211223\Nette\MemberAccessException("Cannot write to a read-only property {$class}::\${$name}.");
            }
            $this->{'set' . $name}($value);
        } else {
            \MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::strictSet($class, $name);
        }
    }
    /**
     * @return void
     * @throws MemberAccessException
     */
    public function __unset(string $name)
    {
        $class = static::class;
        if (!\MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::hasProperty($class, $name)) {
            throw new \MonorepoBuilder20211223\Nette\MemberAccessException("Cannot unset the property {$class}::\${$name}.");
        }
    }
    public function __isset(string $name) : bool
    {
        return isset(\MonorepoBuilder20211223\Nette\Utils\ObjectHelpers::getMagicProperties(static::class)[$name]);
    }
}
