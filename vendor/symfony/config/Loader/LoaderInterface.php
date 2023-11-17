<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\Config\Loader;

/**
 * LoaderInterface is the interface implemented by all loader classes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
interface LoaderInterface
{
    /**
     * Loads a resource.
     *
     * @return mixed
     *
     * @throws \Exception If something went wrong
     * @param mixed $resource
     */
    public function load($resource, string $type = null);
    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     *
     * @return bool
     */
    public function supports($resource, string $type = null);
    /**
     * Gets the loader resolver.
     *
     * @return LoaderResolverInterface
     */
    public function getResolver();
    /**
     * Sets the loader resolver.
     *
     * @return void
     */
    public function setResolver(LoaderResolverInterface $resolver);
}
