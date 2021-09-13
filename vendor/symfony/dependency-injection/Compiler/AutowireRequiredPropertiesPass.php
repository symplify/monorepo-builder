<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Compiler;

use MonorepoBuilder20210913\Symfony\Component\DependencyInjection\ContainerInterface;
use MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Definition;
use MonorepoBuilder20210913\Symfony\Component\DependencyInjection\TypedReference;
use MonorepoBuilder20210913\Symfony\Contracts\Service\Attribute\Required;
/**
 * Looks for definitions with autowiring enabled and registers their corresponding "@required" properties.
 *
 * @author Sebastien Morel (Plopix) <morel.seb@gmail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
class AutowireRequiredPropertiesPass extends \MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass
{
    /**
     * {@inheritdoc}
     * @param bool $isRoot
     */
    protected function processValue($value, $isRoot = \false)
    {
        if (\PHP_VERSION_ID < 70400) {
            return $value;
        }
        $value = parent::processValue($value, $isRoot);
        if (!$value instanceof \MonorepoBuilder20210913\Symfony\Component\DependencyInjection\Definition || !$value->isAutowired() || $value->isAbstract() || !$value->getClass()) {
            return $value;
        }
        if (!($reflectionClass = $this->container->getReflectionClass($value->getClass(), \false))) {
            return $value;
        }
        $properties = $value->getProperties();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (!($type = $reflectionProperty->getType()) instanceof \ReflectionNamedType) {
                continue;
            }
            if ((\PHP_VERSION_ID < 80000 || !$reflectionProperty->getAttributes(\MonorepoBuilder20210913\Symfony\Contracts\Service\Attribute\Required::class)) && (\false === ($doc = $reflectionProperty->getDocComment()) || \false === \stripos($doc, '@required') || !\preg_match('#(?:^/\\*\\*|\\n\\s*+\\*)\\s*+@required(?:\\s|\\*/$)#i', $doc))) {
                continue;
            }
            if (\array_key_exists($name = $reflectionProperty->getName(), $properties)) {
                continue;
            }
            $type = $type->getName();
            $value->setProperty($name, new \MonorepoBuilder20210913\Symfony\Component\DependencyInjection\TypedReference($type, $type, \MonorepoBuilder20210913\Symfony\Component\DependencyInjection\ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $name));
        }
        return $value;
    }
}
