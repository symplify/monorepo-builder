<?php

declare (strict_types=1);
namespace MonorepoBuilder20220501\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass;

use MonorepoBuilder20220501\Nette\Utils\Strings;
use ReflectionClass;
use ReflectionMethod;
use MonorepoBuilder20220501\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder20220501\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20220501\Symfony\Component\DependencyInjection\Definition;
use MonorepoBuilder20220501\Symfony\Component\DependencyInjection\Reference;
use MonorepoBuilder20220501\Symplify\AutowireArrayParameter\DependencyInjection\DefinitionFinder;
use MonorepoBuilder20220501\Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver;
use MonorepoBuilder20220501\Symplify\AutowireArrayParameter\Skipper\ParameterSkipper;
use MonorepoBuilder20220501\Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver;
use MonorepoBuilder20220501\Symplify\PackageBuilder\ValueObject\MethodName;
/**
 * @inspiration https://github.com/nette/di/pull/178
 * @see \Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPassTest
 */
final class AutowireArrayParameterCompilerPass implements \MonorepoBuilder20220501\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
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
     * @noRector
     */
    private $excludedFatalClasses = ['MonorepoBuilder20220501\\Symfony\\Component\\Form\\FormExtensionInterface', 'MonorepoBuilder20220501\\Symfony\\Component\\Asset\\PackageInterface', 'MonorepoBuilder20220501\\Symfony\\Component\\Config\\Loader\\LoaderInterface', 'MonorepoBuilder20220501\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface', 'MonorepoBuilder20220501\\EasyCorp\\Bundle\\EasyAdminBundle\\Form\\Type\\Configurator\\TypeConfiguratorInterface', 'MonorepoBuilder20220501\\Sonata\\CoreBundle\\Model\\Adapter\\AdapterInterface', 'MonorepoBuilder20220501\\Sonata\\Doctrine\\Adapter\\AdapterChain', 'MonorepoBuilder20220501\\Sonata\\Twig\\Extension\\TemplateExtension', 'MonorepoBuilder20220501\\Symfony\\Component\\HttpKernel\\KernelInterface'];
    /**
     * @var \Symplify\AutowireArrayParameter\DependencyInjection\DefinitionFinder
     */
    private $definitionFinder;
    /**
     * @var \Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver
     */
    private $parameterTypeResolver;
    /**
     * @var \Symplify\AutowireArrayParameter\Skipper\ParameterSkipper
     */
    private $parameterSkipper;
    /**
     * @param string[] $excludedFatalClasses
     */
    public function __construct(array $excludedFatalClasses = [])
    {
        $this->definitionFinder = new \MonorepoBuilder20220501\Symplify\AutowireArrayParameter\DependencyInjection\DefinitionFinder();
        $paramTypeDocBlockResolver = new \MonorepoBuilder20220501\Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver();
        $this->parameterTypeResolver = new \MonorepoBuilder20220501\Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver($paramTypeDocBlockResolver);
        $this->parameterSkipper = new \MonorepoBuilder20220501\Symplify\AutowireArrayParameter\Skipper\ParameterSkipper($this->parameterTypeResolver, $excludedFatalClasses);
    }
    public function process(\MonorepoBuilder20220501\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
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
    private function shouldSkipDefinition(\MonorepoBuilder20220501\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, \MonorepoBuilder20220501\Symfony\Component\DependencyInjection\Definition $definition) : bool
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
        if (\MonorepoBuilder20220501\Nette\Utils\Strings::match($resolvedClassName, $excludedNamespacePattern)) {
            return \true;
        }
        if (\in_array($resolvedClassName, $this->excludedFatalClasses, \true)) {
            return \true;
        }
        if ($definition->getFactory()) {
            return \true;
        }
        if (!\class_exists($definition->getClass())) {
            return \true;
        }
        $reflectionClass = $containerBuilder->getReflectionClass($definition->getClass());
        if (!$reflectionClass instanceof \ReflectionClass) {
            return \true;
        }
        if (!$reflectionClass->hasMethod(\MonorepoBuilder20220501\Symplify\PackageBuilder\ValueObject\MethodName::CONSTRUCTOR)) {
            return \true;
        }
        /** @var ReflectionMethod $constructorReflectionMethod */
        $constructorReflectionMethod = $reflectionClass->getConstructor();
        return !$constructorReflectionMethod->getParameters();
    }
    private function processParameters(\MonorepoBuilder20220501\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, \ReflectionMethod $reflectionMethod, \MonorepoBuilder20220501\Symfony\Component\DependencyInjection\Definition $definition) : void
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
            $references[] = new \MonorepoBuilder20220501\Symfony\Component\DependencyInjection\Reference($definitionOfTypeName);
        }
        return $references;
    }
}
