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

use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Alias;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ChildDefinition;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Definition;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Reference;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ResolveDecoratorStackPass implements \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    public function process(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $stacks = [];
        foreach ($container->findTaggedServiceIds('container.stack') as $id => $tags) {
            $definition = $container->getDefinition($id);
            if (!$definition instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ChildDefinition) {
                throw new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Invalid service "%s": only definitions with a "parent" can have the "container.stack" tag.', $id));
            }
            if (!($stack = $definition->getArguments())) {
                throw new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Invalid service "%s": the stack of decorators is empty.', $id));
            }
            $stacks[$id] = $stack;
        }
        if (!$stacks) {
            return;
        }
        $resolvedDefinitions = [];
        foreach ($container->getDefinitions() as $id => $definition) {
            if (!isset($stacks[$id])) {
                $resolvedDefinitions[$id] = $definition;
                continue;
            }
            foreach (\array_reverse($this->resolveStack($stacks, [$id]), \true) as $k => $v) {
                $resolvedDefinitions[$k] = $v;
            }
            $alias = $container->setAlias($id, $k);
            if ($definition->getChanges()['public'] ?? \false) {
                $alias->setPublic($definition->isPublic());
            }
            if ($definition->isDeprecated()) {
                $alias->setDeprecated(...\array_values($definition->getDeprecation('%alias_id%')));
            }
        }
        $container->setDefinitions($resolvedDefinitions);
    }
    private function resolveStack(array $stacks, array $path) : array
    {
        $definitions = [];
        $id = \end($path);
        $prefix = '.' . $id . '.';
        if (!isset($stacks[$id])) {
            return [$id => new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ChildDefinition($id)];
        }
        if (\key($path) !== ($searchKey = \array_search($id, $path))) {
            throw new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException($id, \array_slice($path, $searchKey));
        }
        foreach ($stacks[$id] as $k => $definition) {
            if ($definition instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ChildDefinition && isset($stacks[$definition->getParent()])) {
                $path[] = $definition->getParent();
                $definition = \unserialize(\serialize($definition));
                // deep clone
            } elseif ($definition instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Definition) {
                $definitions[$decoratedId = $prefix . $k] = $definition;
                continue;
            } elseif ($definition instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Reference || $definition instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Alias) {
                $path[] = (string) $definition;
            } else {
                throw new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Invalid service "%s": unexpected value of type "%s" found in the stack of decorators.', $id, \get_debug_type($definition)));
            }
            $p = $prefix . $k;
            foreach ($this->resolveStack($stacks, $path) as $k => $v) {
                $definitions[$decoratedId = $p . $k] = $definition instanceof \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ChildDefinition ? $definition->setParent($k) : new \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ChildDefinition($k);
                $definition = null;
            }
            \array_pop($path);
        }
        if (1 === \count($path)) {
            foreach ($definitions as $k => $definition) {
                $definition->setPublic(\false)->setTags([])->setDecoratedService($decoratedId);
            }
            $definition->setDecoratedService(null);
        }
        return $definitions;
    }
}
