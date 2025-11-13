<?php

declare(strict_types=1);

namespace Symplify\PackageBuilder\DependencyInjection\FileLoader;

use Closure;
use InvalidArgumentException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symplify\PackageBuilder\Yaml\ParametersMerger;

/**
 * @api
 *
 * The need:
 * - https://github.com/symfony/symfony/issues/26713
 * - https://github.com/symfony/symfony/pull/21313#issuecomment-372037445
 *
 * @property ContainerBuilder $container
 */
final class ParameterMergingPhpFileLoader extends PhpFileLoader
{
    private readonly ParametersMerger $parametersMerger;

    public function __construct(ContainerBuilder $containerBuilder, FileLocatorInterface $fileLocator)
    {
        $this->parametersMerger = new ParametersMerger();

        parent::__construct($containerBuilder, $fileLocator);
    }

    /**
     * Same as parent, just merging parameters instead overriding them
     * and supporting custom configurator classes like MBConfig
     *
     * @see https://github.com/symplify/symplify/pull/697
     */
    public function load(mixed $resource, ?string $type = null): mixed
    {
        // get old parameters
        $parameterBag = $this->container->getParameterBag();
        $oldParameters = $parameterBag->all();

        // Custom loading logic to support MBConfig
        $this->loadWithCustomConfigurator($resource);

        foreach ($oldParameters as $key => $oldValue) {
            if ($this->container->hasParameter($key)) {
                $currentParameterValue = $this->container->getParameter($key);
                $newValue = $this->parametersMerger->merge($oldValue, $currentParameterValue);
                $this->container->setParameter($key, $newValue);
            }
        }

        return null;
    }

    private function loadWithCustomConfigurator(mixed $resource): void
    {
        // the container and loader variables are exposed to the included file below
        $container = $this->container;
        $loader = $this;

        $path = $this->locator->locate($resource);
        $this->setCurrentDir(\dirname($path));
        $this->container->fileExists($path);

        // the closure forbids access to the private scope in the included file
        $load = Closure::bind(fn ($path, $env) => include $path, $this, ProtectedPhpFileLoader::class);

        try {
            $callback = $load($path, $this->env);

            if (\is_object($callback) && \is_callable($callback)) {
                // Check the callback's first parameter type to determine configurator class
                $configuratorClass = $this->getConfiguratorClass($callback, $path);
                $configurator = $this->createConfigurator($configuratorClass, $resource, $path);
                $this->executeCallback($callback, $configurator, $path);
            }
        } finally {
            $this->instanceof = [];
            $this->registerAliasesForSinglyImplementedInterfaces();
        }
    }

    private function getConfiguratorClass(callable $callback, string $path): string
    {
        $reflectionFunction = new ReflectionFunction(Closure::fromCallable($callback));
        $parameters = $reflectionFunction->getParameters();

        if (empty($parameters)) {
            throw new InvalidArgumentException(sprintf('The config file "%s" must define a callable with at least one parameter.', $path));
        }

        $firstParam = $parameters[0];
        $type = $firstParam->getType();

        if (! $type instanceof ReflectionNamedType) {
            throw new InvalidArgumentException(sprintf('The first parameter of the callable in "%s" must have a type hint.', $path));
        }

        $className = $type->getName();

        // Check if the class exists and is a subclass of ContainerConfigurator
        if (! class_exists($className)) {
            throw new InvalidArgumentException(sprintf('The type "%s" specified in "%s" does not exist.', $className, $path));
        }

        if (! is_a($className, ContainerConfigurator::class, true)) {
            throw new InvalidArgumentException(sprintf('The type "%s" must extend ContainerConfigurator.', $className));
        }

        return $className;
    }

    private function createConfigurator(string $configuratorClass, mixed $resource, string $path): ContainerConfigurator
    {
        new FileResource($path);
        /** @var ContainerConfigurator $configurator */
        $configurator = new $configuratorClass($this->container, $this, $this->instanceof, $path, $resource, $this->env);
        return $configurator;
    }

    /**
     * Resolve the parameters to the $callback and execute it.
     */
    private function executeCallback(callable $callback, ContainerConfigurator $containerConfigurator, string $path): void
    {
        $callback = $callback(...);
        $arguments = [];
        $configBuilders = [];
        $reflectionFunction = new ReflectionFunction(Closure::fromCallable($callback));

        foreach ($reflectionFunction->getParameters() as $reflectionParameter) {
            $reflectionType = $reflectionParameter->getType();
            if (! $reflectionType instanceof ReflectionNamedType) {
                throw new InvalidArgumentException(\sprintf('Could not resolve argument "$%s" for "%s". You must typehint it.', $reflectionParameter->getName(), $path));
            }

            $type = $reflectionType->getName();

            // Check if type is a subclass of ContainerConfigurator
            if (is_a($type, ContainerConfigurator::class, true)) {
                $arguments[] = $containerConfigurator;
            } else {
                switch ($type) {
                    case ContainerBuilder::class:
                        $arguments[] = $this->container;
                        break;
                    case FileLoader::class:
                    case self::class:
                        $arguments[] = $this;
                        break;
                    case 'string':
                        if ($this->env !== null && $reflectionParameter->getName() === 'env') {
                            $arguments[] = $this->env;
                            break;
                        }
                        // no break - fall through to default
                    default:
                        try {
                            // Use reflection to call the private configBuilder method
                            $reflectionMethod = new ReflectionMethod(PhpFileLoader::class, 'configBuilder');
                            $configBuilder = $reflectionMethod->invoke($this, $type);
                        } catch (ReflectionException $e) {
                            throw new InvalidArgumentException(sprintf('Could not resolve argument "%s" for "%s".', $type . ' $' . $reflectionParameter->getName(), $path), 0, $e);
                        }

                        $configBuilders[] = $configBuilder;
                        $arguments[] = $configBuilder;
                }
            }
        }

        $callback(...$arguments);

        // ConfigBuilderInterface doesn't have a build() method in all versions
        // Call it dynamically if it exists
        foreach ($configBuilders as $configBuilder) {
            if (is_object($configBuilder) && method_exists($configBuilder, 'build')) {
                $configBuilder->build($this->container);
            }
        }
    }
}

/**
 * Dummy class to use for scoping the include
 */
class ProtectedPhpFileLoader extends PhpFileLoader
{
}
