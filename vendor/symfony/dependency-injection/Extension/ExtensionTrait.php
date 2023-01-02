<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder202301\Symfony\Component\DependencyInjection\Extension;

use MonorepoBuilder202301\Symfony\Component\Config\Builder\ConfigBuilderGenerator;
use MonorepoBuilder202301\Symfony\Component\Config\FileLocator;
use MonorepoBuilder202301\Symfony\Component\Config\Loader\DelegatingLoader;
use MonorepoBuilder202301\Symfony\Component\Config\Loader\LoaderResolver;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use MonorepoBuilder202301\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
/**
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
trait ExtensionTrait
{
    private function executeConfiguratorCallback(ContainerBuilder $container, \Closure $callback, ConfigurableExtensionInterface $subject) : void
    {
        $env = $container->getParameter('kernel.environment');
        $loader = $this->createContainerLoader($container, $env);
        $file = (new \ReflectionObject($subject))->getFileName();
        $bundleLoader = $loader->getResolver()->resolve($file);
        if (!$bundleLoader instanceof PhpFileLoader) {
            throw new \LogicException('Unable to create the ContainerConfigurator.');
        }
        $bundleLoader->setCurrentDir(\dirname($file));
        $instanceof =& \Closure::bind(function &() {
            return $this->instanceof;
        }, $bundleLoader, $bundleLoader)();
        try {
            $callback(new ContainerConfigurator($container, $bundleLoader, $instanceof, $file, $file, $env));
        } finally {
            $instanceof = [];
            $bundleLoader->registerAliasesForSinglyImplementedInterfaces();
        }
    }
    private function createContainerLoader(ContainerBuilder $container, string $env) : DelegatingLoader
    {
        $buildDir = $container->getParameter('kernel.build_dir');
        $locator = new FileLocator();
        $resolver = new LoaderResolver([new XmlFileLoader($container, $locator, $env), new YamlFileLoader($container, $locator, $env), new IniFileLoader($container, $locator, $env), new PhpFileLoader($container, $locator, $env, new ConfigBuilderGenerator($buildDir)), new GlobFileLoader($container, $locator, $env), new DirectoryLoader($container, $locator, $env), new ClosureLoader($container, $env)]);
        return new DelegatingLoader($resolver);
    }
}
