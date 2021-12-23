<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator;

use MonorepoBuilder20211223\Symfony\Component\Config\Loader\ParamConfigurator;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\AbstractArgument;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Definition;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use MonorepoBuilder20211223\Symfony\Component\ExpressionLanguage\Expression;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ContainerConfigurator extends \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\AbstractConfigurator
{
    public const FACTORY = 'container';
    private $container;
    private $loader;
    /**
     * @var mixed[]
     */
    private $instanceof;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $file;
    /**
     * @var int
     */
    private $anonymousCount = 0;
    /**
     * @var string|null
     */
    private $env;
    public function __construct(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder $container, \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\PhpFileLoader $loader, array &$instanceof, string $path, string $file, string $env = null)
    {
        $this->container = $container;
        $this->loader = $loader;
        $this->instanceof =& $instanceof;
        $this->path = $path;
        $this->file = $file;
        $this->env = $env;
    }
    public final function extension(string $namespace, array $config)
    {
        if (!$this->container->hasExtension($namespace)) {
            $extensions = \array_filter(\array_map(function (\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Extension\ExtensionInterface $ext) {
                return $ext->getAlias();
            }, $this->container->getExtensions()));
            throw new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('There is no extension able to load the configuration for "%s" (in "%s"). Looked for namespace "%s", found "%s".', $namespace, $this->file, $namespace, $extensions ? \implode('", "', $extensions) : 'none'));
        }
        $this->container->loadFromExtension($namespace, static::processValue($config));
    }
    /**
     * @param bool|string $ignoreErrors
     */
    public final function import(string $resource, string $type = null, $ignoreErrors = \false)
    {
        $this->loader->setCurrentDir(\dirname($this->path));
        $this->loader->import($resource, $type, $ignoreErrors, $this->file);
    }
    public final function parameters() : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ParametersConfigurator
    {
        return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ParametersConfigurator($this->container);
    }
    public final function services() : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator
    {
        return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator($this->container, $this->loader, $this->instanceof, $this->path, $this->anonymousCount);
    }
    /**
     * Get the current environment to be able to write conditional configuration.
     */
    public final function env() : ?string
    {
        return $this->env;
    }
    /**
     * @return $this
     */
    public final function withPath(string $path)
    {
        $clone = clone $this;
        $clone->path = $clone->file = $path;
        $clone->loader->setCurrentDir(\dirname($path));
        return $clone;
    }
}
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
\class_alias('MonorepoBuilder20211223\\Symfony\\Component\\DependencyInjection\\Loader\\Configurator\\ContainerConfigurator', 'Symfony\\Component\\DependencyInjection\\Loader\\Configurator\\ContainerConfigurator', \false);
/**
 * Creates a parameter.
 */
function param(string $name) : \MonorepoBuilder20211223\Symfony\Component\Config\Loader\ParamConfigurator
{
    return new \MonorepoBuilder20211223\Symfony\Component\Config\Loader\ParamConfigurator($name);
}
/**
 * Creates a reference to a service.
 */
function service(string $serviceId) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator($serviceId);
}
/**
 * Creates an inline service.
 */
function inline_service(string $class = null) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\InlineServiceConfigurator
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\InlineServiceConfigurator(new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Definition($class));
}
/**
 * Creates a service locator.
 *
 * @param ReferenceConfigurator[] $values
 */
function service_locator(array $values) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\AbstractConfigurator::processValue($values, \true));
}
/**
 * Creates a lazy iterator.
 *
 * @param ReferenceConfigurator[] $values
 */
function iterator(array $values) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\IteratorArgument
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\IteratorArgument(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\AbstractConfigurator::processValue($values, \true));
}
/**
 * Creates a lazy iterator by tag name.
 */
function tagged_iterator(string $tag, string $indexAttribute = null, string $defaultIndexMethod = null, string $defaultPriorityMethod = null) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument($tag, $indexAttribute, $defaultIndexMethod, \false, $defaultPriorityMethod);
}
/**
 * Creates a service locator by tag name.
 */
function tagged_locator(string $tag, string $indexAttribute = null, string $defaultIndexMethod = null, string $defaultPriorityMethod = null) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument(new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument($tag, $indexAttribute, $defaultIndexMethod, \true, $defaultPriorityMethod));
}
/**
 * Creates an expression.
 */
function expr(string $expression) : \MonorepoBuilder20211223\Symfony\Component\ExpressionLanguage\Expression
{
    return new \MonorepoBuilder20211223\Symfony\Component\ExpressionLanguage\Expression($expression);
}
/**
 * Creates an abstract argument.
 */
function abstract_arg(string $description) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\AbstractArgument
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Argument\AbstractArgument($description);
}
/**
 * Creates an environment variable reference.
 */
function env(string $name) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\EnvConfigurator
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\EnvConfigurator($name);
}
/**
 * Creates a closure service reference.
 */
function service_closure(string $serviceId) : \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ClosureReferenceConfigurator
{
    return new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\Configurator\ClosureReferenceConfigurator($serviceId);
}
