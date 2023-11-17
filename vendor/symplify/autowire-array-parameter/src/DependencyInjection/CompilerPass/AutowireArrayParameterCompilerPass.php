<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202311\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass;

use MonorepoBuilderPrefix202311\Nette\Utils\Strings;
use ReflectionClass;
use ReflectionMethod;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Definition;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Reference;
use MonorepoBuilderPrefix202311\Symplify\AutowireArrayParameter\DependencyInjection\DefinitionFinder;
use MonorepoBuilderPrefix202311\Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver;
use MonorepoBuilderPrefix202311\Symplify\AutowireArrayParameter\Skipper\ParameterSkipper;
use MonorepoBuilderPrefix202311\Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver;
/**
 * @inspiration https://github.com/nette/di/pull/178
 * @see \Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPassTest
 */
final class AutowireArrayParameterCompilerPass implements CompilerPassInterface
{
    /**
     * These namespaces are already configured by their bundles/extensions.
     *
     * @var string[]
     */
    private const EXCLUDED_NAMESPACES = ['Doctrine', 'JMS', 'Symfony', 'Sensio', 'Knp', 'EasyCorp', 'Sonata', 'Twig'];
    /**
     * Classes that create circular dependencies
     *
     * @var string[]
     */
    private const EXCLUDED_FATAL_CLASSES = ['MonorepoBuilderPrefix202311\\Symfony\\Component\\Form\\FormExtensionInterface', 'MonorepoBuilderPrefix202311\\Symfony\\Component\\Asset\\PackageInterface', 'MonorepoBuilderPrefix202311\\Symfony\\Component\\Config\\Loader\\LoaderInterface', 'MonorepoBuilderPrefix202311\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface', 'MonorepoBuilderPrefix202311\\EasyCorp\\Bundle\\EasyAdminBundle\\Form\\Type\\Configurator\\TypeConfiguratorInterface', 'MonorepoBuilderPrefix202311\\Sonata\\CoreBundle\\Model\\Adapter\\AdapterInterface', 'MonorepoBuilderPrefix202311\\Sonata\\Doctrine\\Adapter\\AdapterChain', 'MonorepoBuilderPrefix202311\\Sonata\\Twig\\Extension\\TemplateExtension', 'MonorepoBuilderPrefix202311\\Symfony\\Component\\HttpKernel\\KernelInterface'];
    /**
     * @readonly
     * @var \Symplify\AutowireArrayParameter\DependencyInjection\DefinitionFinder
     */
    private $definitionFinder;
    /**
     * @readonly
     * @var \Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver
     */
    private $parameterTypeResolver;
    /**
     * @readonly
     * @var \Symplify\AutowireArrayParameter\Skipper\ParameterSkipper
     */
    private $parameterSkipper;
    /**
     * @param string[] $excludedFatalClasses
     */
    public function __construct(array $excludedFatalClasses = [])
    {
        $this->definitionFinder = new DefinitionFinder();
        $paramTypeDocBlockResolver = new ParamTypeDocBlockResolver();
        $this->parameterTypeResolver = new ParameterTypeResolver($paramTypeDocBlockResolver);
        $this->parameterSkipper = new ParameterSkipper($this->parameterTypeResolver, $excludedFatalClasses);
    }
    public function process(ContainerBuilder $containerBuilder) : void
    {
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            if ($this->shouldSkipDefinition($containerBuilder, $definition)) {
                continue;
            }
            /** @var ReflectionClass<object> $reflectionClass */
            $reflectionClass = $containerBuilder->getReflectionClass($definition->getClass());
            /** @var ReflectionMethod $constructorReflectionMethod */
            $constructorReflectionMethod = $reflectionClass->getConstructor();
            $this->processParameters($containerBuilder, $constructorReflectionMethod, $definition);
        }
    }
    private function shouldSkipDefinition(ContainerBuilder $containerBuilder, Definition $definition) : bool
    {
        if ($definition->isAbstract()) {
            return \true;
        }
        if ($definition->getClass() === null) {
            return \true;
        }
        // here class name can be "%parameter.class%"
        $parameterBag = $containerBuilder->getParameterBag();
        $resolvedClassName = $parameterBag->resolveValue($definition->getClass());
        // skip 3rd party classes, they're autowired by own config
        $excludedNamespacePattern = '#^(' . \implode('|', self::EXCLUDED_NAMESPACES) . ')\\\\#';
        if (Strings::match($resolvedClassName, $excludedNamespacePattern)) {
            return \true;
        }
        if (\in_array($resolvedClassName, self::EXCLUDED_FATAL_CLASSES, \true)) {
            return \true;
        }
        if ($definition->getFactory()) {
            return \true;
        }
        if (!\class_exists($definition->getClass())) {
            return \true;
        }
        $reflectionClass = $containerBuilder->getReflectionClass($definition->getClass());
        if (!$reflectionClass instanceof ReflectionClass) {
            return \true;
        }
        if (!$reflectionClass->hasMethod('__construct')) {
            return \true;
        }
        /** @var ReflectionMethod $constructorReflectionMethod */
        $constructorReflectionMethod = $reflectionClass->getConstructor();
        return !$constructorReflectionMethod->getParameters();
    }
    private function processParameters(ContainerBuilder $containerBuilder, ReflectionMethod $reflectionMethod, Definition $definition) : void
    {
        $reflectionParameters = $reflectionMethod->getParameters();
        foreach ($reflectionParameters as $reflectionParameter) {
            if ($this->parameterSkipper->shouldSkipParameter($reflectionMethod, $definition, $reflectionParameter)) {
                continue;
            }
            $parameterType = $this->parameterTypeResolver->resolveParameterType($reflectionParameter->getName(), $reflectionMethod);
            if ($parameterType === null) {
                continue;
            }
            $definitionsOfType = $this->definitionFinder->findAllByType($containerBuilder, $parameterType);
            $definitionsOfType = $this->filterOutAbstractDefinitions($definitionsOfType);
            $argumentName = '$' . $reflectionParameter->getName();
            $definition->setArgument($argumentName, $this->createReferencesFromDefinitions($definitionsOfType));
        }
    }
    /**
     * Abstract definitions cannot be the target of references
     *
     * @param Definition[] $definitions
     * @return Definition[]
     */
    private function filterOutAbstractDefinitions(array $definitions) : array
    {
        foreach ($definitions as $key => $definition) {
            if ($definition->isAbstract()) {
                unset($definitions[$key]);
            }
        }
        return $definitions;
    }
    /**
     * @param Definition[] $definitions
     * @return Reference[]
     */
    private function createReferencesFromDefinitions(array $definitions) : array
    {
        $references = [];
        $definitionOfTypeNames = \array_keys($definitions);
        foreach ($definitionOfTypeNames as $definitionOfTypeName) {
            $references[] = new Reference($definitionOfTypeName);
        }
        return $references;
    }
}
