<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210707\Symfony\Component\HttpKernel\DependencyInjection;

use MonorepoBuilder20210707\Psr\Log\LoggerInterface;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210707\Symfony\Component\HttpKernel\Log\Logger;
/**
 * Registers the default logger if necessary.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class LoggerPass implements \MonorepoBuilder20210707\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(\MonorepoBuilder20210707\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $container->setAlias(\MonorepoBuilder20210707\Psr\Log\LoggerInterface::class, 'logger')->setPublic(\false);
        if ($container->has('logger')) {
            return;
        }
        $container->register('logger', \MonorepoBuilder20210707\Symfony\Component\HttpKernel\Log\Logger::class)->setPublic(\false);
    }
}
