<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Loader\Configurator\Traits;

trait PublicTrait
{
    /**
     * @return $this
     */
    public final function public()
    {
        $this->definition->setPublic(\true);
        return $this;
    }
    /**
     * @return $this
     */
    public final function private()
    {
        $this->definition->setPublic(\false);
        return $this;
    }
}
