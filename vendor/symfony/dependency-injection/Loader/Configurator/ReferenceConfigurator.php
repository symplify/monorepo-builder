<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\Loader\Configurator;

use MonorepoBuilderPrefix202311\Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ReferenceConfigurator extends AbstractConfigurator
{
    /** @internal
     * @var string */
    protected $id;
    /** @internal
     * @var int */
    protected $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
    public function __construct(string $id)
    {
        $this->id = $id;
    }
    /**
     * @return $this
     */
    public final function ignoreOnInvalid()
    {
        $this->invalidBehavior = ContainerInterface::IGNORE_ON_INVALID_REFERENCE;
        return $this;
    }
    /**
     * @return $this
     */
    public final function nullOnInvalid()
    {
        $this->invalidBehavior = ContainerInterface::NULL_ON_INVALID_REFERENCE;
        return $this;
    }
    /**
     * @return $this
     */
    public final function ignoreOnUninitialized()
    {
        $this->invalidBehavior = ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE;
        return $this;
    }
    public function __toString() : string
    {
        return $this->id;
    }
}
