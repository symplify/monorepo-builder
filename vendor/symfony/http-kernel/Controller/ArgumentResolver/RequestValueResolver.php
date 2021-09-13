<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20210913\Symfony\Component\HttpKernel\Controller\ArgumentResolver;

use MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Request;
use MonorepoBuilder20210913\Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use MonorepoBuilder20210913\Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
/**
 * Yields the same instance as the request object passed along.
 *
 * @author Iltar van der Berg <kjarli@gmail.com>
 */
final class RequestValueResolver implements \MonorepoBuilder20210913\Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata $argument
     */
    public function supports($request, $argument) : bool
    {
        return \MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Request::class === $argument->getType() || \is_subclass_of($argument->getType(), \MonorepoBuilder20210913\Symfony\Component\HttpFoundation\Request::class);
    }
    /**
     * {@inheritdoc}
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata $argument
     */
    public function resolve($request, $argument) : iterable
    {
        (yield $request);
    }
}
