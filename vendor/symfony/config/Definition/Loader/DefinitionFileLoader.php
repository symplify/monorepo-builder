<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\Config\Definition\Loader;

use MonorepoBuilderPrefix202311\Symfony\Component\Config\Definition\Builder\TreeBuilder;
use MonorepoBuilderPrefix202311\Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use MonorepoBuilderPrefix202311\Symfony\Component\Config\FileLocatorInterface;
use MonorepoBuilderPrefix202311\Symfony\Component\Config\Loader\FileLoader;
use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * DefinitionFileLoader loads config definitions from a PHP file.
 *
 * The PHP file is required.
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class DefinitionFileLoader extends FileLoader
{
    /**
     * @var \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    private $treeBuilder;
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder|null
     */
    private $container;
    public function __construct(TreeBuilder $treeBuilder, FileLocatorInterface $locator, ?ContainerBuilder $container = null)
    {
        $this->treeBuilder = $treeBuilder;
        $this->container = $container;
        parent::__construct($locator);
    }
    /**
     * @param mixed $resource
     * @return mixed
     */
    public function load($resource, string $type = null)
    {
        // the loader variable is exposed to the included file below
        $loader = $this;
        $path = $this->locator->locate($resource);
        $this->setCurrentDir(\dirname($path));
        ($nullsafeVariable1 = $this->container) ? $nullsafeVariable1->fileExists($path) : null;
        // the closure forbids access to the private scope in the included file
        $load = \Closure::bind(static function ($file) use($loader) {
            return include $file;
        }, null, ProtectedDefinitionFileLoader::class);
        $callback = $load($path);
        if (\is_object($callback) && \is_callable($callback)) {
            $this->executeCallback($callback, new DefinitionConfigurator($this->treeBuilder, $this, $path, $resource), $path);
        }
        return null;
    }
    /**
     * @param mixed $resource
     */
    public function supports($resource, string $type = null) : bool
    {
        if (!\is_string($resource)) {
            return \false;
        }
        if (null === $type && 'php' === \pathinfo($resource, \PATHINFO_EXTENSION)) {
            return \true;
        }
        return 'php' === $type;
    }
    private function executeCallback(callable $callback, DefinitionConfigurator $configurator, string $path) : void
    {
        $callback = \Closure::fromCallable($callback);
        $arguments = [];
        $r = new \ReflectionFunction($callback);
        foreach ($r->getParameters() as $parameter) {
            $reflectionType = $parameter->getType();
            if (!$reflectionType instanceof \ReflectionNamedType) {
                throw new \InvalidArgumentException(\sprintf('Could not resolve argument "$%s" for "%s". You must typehint it (for example with "%s").', $parameter->getName(), $path, DefinitionConfigurator::class));
            }
            switch ($reflectionType->getName()) {
                case DefinitionConfigurator::class:
                    $arguments[] = $configurator;
                    break;
                case TreeBuilder::class:
                    $arguments[] = $this->treeBuilder;
                    break;
                case FileLoader::class:
                case self::class:
                    $arguments[] = $this;
                    break;
            }
        }
        $callback(...$arguments);
    }
}
/**
 * @internal
 */
final class ProtectedDefinitionFileLoader extends DefinitionFileLoader
{
}
