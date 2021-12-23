<?php

declare (strict_types=1);
namespace MonorepoBuilder20211223\Symplify\PackageBuilder\DependencyInjection\FileLoader;

use MonorepoBuilder20211223\Symfony\Component\Config\FileLocatorInterface;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Yaml\ParametersMerger;
/**
 * @api
 *
 * The need:
 * - https://github.com/symfony/symfony/issues/26713
 * - https://github.com/symfony/symfony/pull/21313#issuecomment-372037445
 */
final class ParameterMergingPhpFileLoader extends \MonorepoBuilder20211223\Symfony\Component\DependencyInjection\Loader\PhpFileLoader
{
    /**
     * @var \Symplify\PackageBuilder\Yaml\ParametersMerger
     */
    private $parametersMerger;
    public function __construct(\MonorepoBuilder20211223\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, \MonorepoBuilder20211223\Symfony\Component\Config\FileLocatorInterface $fileLocator)
    {
        $this->parametersMerger = new \MonorepoBuilder20211223\Symplify\PackageBuilder\Yaml\ParametersMerger();
        parent::__construct($containerBuilder, $fileLocator);
    }
    /**
     * Same as parent, just merging parameters instead overriding them
     *
     * @see https://github.com/symplify/symplify/pull/697
     * @param mixed $resource
     * @return mixed
     * @param string|null $type
     */
    public function load($resource, $type = null)
    {
        // get old parameters
        $parameterBag = $this->container->getParameterBag();
        $oldParameters = $parameterBag->all();
        parent::load($resource);
        foreach ($oldParameters as $key => $oldValue) {
            $newValue = $this->parametersMerger->merge($oldValue, $this->container->getParameter($key));
            $this->container->setParameter($key, $newValue);
        }
        return null;
    }
}
