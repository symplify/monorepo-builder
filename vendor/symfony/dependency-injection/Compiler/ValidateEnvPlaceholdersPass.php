<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler;

use MonorepoBuilder20211223\Symfony\Component\Config\Definition\BaseNode;
use MonorepoBuilder20211223\Symfony\Component\Config\Definition\ConfigurationInterface;
use MonorepoBuilder20211223\Symfony\Component\Config\Definition\Processor;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
/**
 * Validates environment variable placeholders used in extension configuration with dummy values.
 *
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
class ValidateEnvPlaceholdersPass implements \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    private const TYPE_FIXTURES = ['array' => [], 'bool' => \false, 'float' => 0.0, 'int' => 0, 'string' => ''];
    /**
     * @var mixed[]
     */
    private $extensionConfig = [];
    /**
     * {@inheritdoc}
     */
    public function process(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $this->extensionConfig = [];
        if (!\class_exists(\MonorepoBuilder20211223\Symfony\Component\Config\Definition\BaseNode::class) || !($extensions = $container->getExtensions())) {
            return;
        }
        $resolvingBag = $container->getParameterBag();
        if (!$resolvingBag instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ParameterBag\EnvPlaceholderParameterBag) {
            return;
        }
        $defaultBag = new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ParameterBag\ParameterBag($resolvingBag->all());
        $envTypes = $resolvingBag->getProvidedTypes();
        try {
            foreach ($resolvingBag->getEnvPlaceholders() + $resolvingBag->getUnusedEnvPlaceholders() as $env => $placeholders) {
                $values = [];
                if (\false === ($i = \strpos($env, ':'))) {
                    $default = $defaultBag->has("env({$env})") ? $defaultBag->get("env({$env})") : self::TYPE_FIXTURES['string'];
                    $defaultType = null !== $default ? \get_debug_type($default) : 'string';
                    $values[$defaultType] = $default;
                } else {
                    $prefix = \substr($env, 0, $i);
                    foreach ($envTypes[$prefix] ?? ['string'] as $type) {
                        $values[$type] = self::TYPE_FIXTURES[$type] ?? null;
                    }
                }
                foreach ($placeholders as $placeholder) {
                    \MonorepoBuilder20211223\Symfony\Component\Config\Definition\BaseNode::setPlaceholder($placeholder, $values);
                }
            }
            $processor = new \MonorepoBuilder20211223\Symfony\Component\Config\Definition\Processor();
            foreach ($extensions as $name => $extension) {
                if (!($extension instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface || $extension instanceof \MonorepoBuilder20211223\Symfony\Component\Config\Definition\ConfigurationInterface) || !($config = \array_filter($container->getExtensionConfig($name)))) {
                    // this extension has no semantic configuration or was not called
                    continue;
                }
                $config = $resolvingBag->resolveValue($config);
                if ($extension instanceof \MonorepoBuilder20211223\Symfony\Component\Config\Definition\ConfigurationInterface) {
                    $configuration = $extension;
                } elseif (null === ($configuration = $extension->getConfiguration($config, $container))) {
                    continue;
                }
                $this->extensionConfig[$name] = $processor->processConfiguration($configuration, $config);
            }
        } finally {
            \MonorepoBuilder20211223\Symfony\Component\Config\Definition\BaseNode::resetPlaceholders();
        }
        $resolvingBag->clearUnusedEnvPlaceholders();
    }
    /**
     * @internal
     */
    public function getExtensionConfig() : array
    {
        try {
            return $this->extensionConfig;
        } finally {
            $this->extensionConfig = [];
        }
    }
}
