<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace MonorepoBuilder20220418\Symfony\Component\DependencyInjection\Loader\Configurator;

use MonorepoBuilder20220418\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20220418\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use MonorepoBuilder20220418\Symfony\Component\ExpressionLanguage\Expression;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ParametersConfigurator extends \MonorepoBuilder20220418\Symfony\Component\DependencyInjection\Loader\Configurator\AbstractConfigurator
{
    public const FACTORY = 'parameters';
    private $container;
    public function __construct(\MonorepoBuilder20220418\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $this->container = $container;
    }
    /**
     * @return $this
     * @param mixed $value
     */
    public final function set(string $name, $value)
    {
        if ($value instanceof \MonorepoBuilder20220418\Symfony\Component\ExpressionLanguage\Expression) {
            throw new \MonorepoBuilder20220418\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException(\sprintf('Using an expression in parameter "%s" is not allowed.', $name));
        }
        $this->container->setParameter($name, static::processValue($value, \true));
        return $this;
    }
    /**
     * @return $this
     * @param mixed $value
     */
    public final function __invoke(string $name, $value)
    {
        return $this->set($name, $value);
    }
}
