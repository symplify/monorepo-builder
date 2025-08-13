<?php

declare(strict_types=1);

namespace Symplify\PackageBuilder\Reflection;

use ReflectionClass;
use ReflectionMethod;

/**
 * @see \Symplify\PackageBuilder\Tests\Reflection\PrivatesCallerTest
 */
final class PrivatesCaller
{
    /**
     * @api
     * @param mixed[] $arguments
     */
    public function callPrivateMethod(object | string $object, string $methodName, array $arguments): mixed
    {
        if (is_string($object)) {
            $reflectionClass = new ReflectionClass($object);
            $object = $reflectionClass->newInstanceWithoutConstructor();
        }

        $reflectionMethod = $this->createAccessibleMethodReflection($object, $methodName);

        return $reflectionMethod->invokeArgs($object, $arguments);
    }

    /**
     * @api
     */
    public function callPrivateMethodWithReference(object | string $object, string $methodName, mixed $argument): mixed
    {
        if (is_string($object)) {
            $reflectionClass = new ReflectionClass($object);
            $object = $reflectionClass->newInstanceWithoutConstructor();
        }

        $reflectionMethod = $this->createAccessibleMethodReflection($object, $methodName);
        $reflectionMethod->invokeArgs($object, [&$argument]);

        return $argument;
    }

    private function createAccessibleMethodReflection(object $object, string $methodName): ReflectionMethod
    {
        return new ReflectionMethod($object::class, $methodName);
    }
}
