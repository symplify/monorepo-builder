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
 * GlobFileLoader loads files from a glob pattern.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class GlobFileLoader extends FileLoader
{
    /**
     * @param mixed $resource
     * @return mixed
     */
    public function load($resource, string $type = null)
    {
        return $this->import($resource);
    }
    /**
     * @param mixed $resource
     */
    public function supports($resource, string $type = null) : bool
    {
        return 'glob' === $type;
    }
}
