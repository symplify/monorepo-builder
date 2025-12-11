<?php

declare(strict_types=1);

namespace Symplify\AutowireArrayParameter\Skipper;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\VarDumper\Dumper\ContextProvider\ContextProviderInterface;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver;

final class ParameterSkipper
{
    /**
     * Classes that create circular dependencies
     *
     * @var string[]
     */
    private const DEFAULT_EXCLUDED_FATAL_CLASSES = [
        'Symfony\Component\Form\FormExtensionInterface',
        'Symfony\Component\Asset\PackageInterface',
        LoaderInterface::class,
        ContextProviderInterface::class,
        'EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\TypeConfiguratorInterface',
        'Sonata\CoreBundle\Model\Adapter\AdapterInterface',
        'Sonata\Doctrine\Adapter\AdapterChain',
        'Sonata\Twig\Extension\TemplateExtension',
    ];

    /**
     * @var string[]
     */
    private array $excludedFatalClasses = [];

    /**
     * @param string[] $excludedFatalClasses
     */
    public function __construct(
        private readonly ParameterTypeResolver $parameterTypeResolver,
        array $excludedFatalClasses
    ) {
        $this->excludedFatalClasses = array_merge(self::DEFAULT_EXCLUDED_FATAL_CLASSES, $excludedFatalClasses);
    }

    public function shouldSkipParameter(
        ReflectionMethod $reflectionMethod,
        Definition $definition,
        ReflectionParameter $reflectionParameter
    ): bool {
        if (! $this->isArrayType($reflectionParameter)) {
            return true;
        }

        // already set
        $argumentName = '$' . $reflectionParameter->getName();
        if (isset($definition->getArguments()[$argumentName])) {
            return true;
        }

        $parameterType = $this->parameterTypeResolver->resolveParameterType(
            $reflectionParameter->getName(),
            $reflectionMethod
        );

        if ($parameterType === null) {
            return true;
        }

        if (in_array($parameterType, $this->excludedFatalClasses, true)) {
            return true;
        }

        if (! class_exists($parameterType) && ! interface_exists($parameterType)) {
            return true;
        }

        // prevent circular dependency
        if ($definition->getClass() === null) {
            return false;
        }

        return is_a($definition->getClass(), $parameterType, true);
    }

    private function isArrayType(ReflectionParameter $reflectionParameter): bool
    {
        if (! $reflectionParameter->getType() instanceof ReflectionType) {
            return false;
        }

        $reflectionType = $reflectionParameter->getType();
        if (! $reflectionType instanceof ReflectionNamedType) {
            return false;
        }

        return $reflectionType->getName() === 'array';
    }
}
