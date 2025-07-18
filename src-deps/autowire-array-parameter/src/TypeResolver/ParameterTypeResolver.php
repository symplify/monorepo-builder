<?php

declare(strict_types=1);

namespace Symplify\AutowireArrayParameter\TypeResolver;

use Nette\Utils\Reflection;
use ReflectionMethod;
use Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver;

final class ParameterTypeResolver
{
    /**
     * @var array<string, string>
     */
    private array $resolvedParameterTypesCached = [];

    public function __construct(
        private readonly ParamTypeDocBlockResolver $paramTypeDocBlockResolver
    ) {
    }

    public function resolveParameterType(string $parameterName, ReflectionMethod $reflectionMethod): ?string
    {
        $docComment = $reflectionMethod->getDocComment();
        if ($docComment === false) {
            return null;
        }

        $declaringReflectionClass = $reflectionMethod->getDeclaringClass();
        $uniqueKey = $parameterName . $declaringReflectionClass->getName() . $reflectionMethod->getName();
        if (isset($this->resolvedParameterTypesCached[$uniqueKey])) {
            return $this->resolvedParameterTypesCached[$uniqueKey];
        }

        $resolvedType = $this->paramTypeDocBlockResolver->resolve($docComment, $parameterName);
        if ($resolvedType === null) {
            return null;
        }

        // not a class|interface type
        if (ctype_lower($resolvedType[0])) {
            return null;
        }

        $resolvedClass = Reflection::expandClassName($resolvedType, $declaringReflectionClass);
        $this->resolvedParameterTypesCached[$uniqueKey] = $resolvedClass;

        return $resolvedClass;
    }
}
