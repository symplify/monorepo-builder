<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\HttpKernel;

use MonorepoBuilder20211101\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20211101\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20211101\Symfony\Component\HttpKernel\Bundle\BundleInterface;
use MonorepoBuilder20211101\Symplify\ComposerJsonManipulator\Bundle\ComposerJsonManipulatorBundle;
use MonorepoBuilder20211101\Symplify\ConsoleColorDiff\Bundle\ConsoleColorDiffBundle;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20211101\Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use MonorepoBuilder20211101\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle;
use MonorepoBuilder20211101\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class MonorepoBuilderKernel extends \MonorepoBuilder20211101\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     */
    public function registerContainerConfiguration($loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
        parent::registerContainerConfiguration($loader);
    }
    /**
     * @return BundleInterface[]
     */
    public function registerBundles() : iterable
    {
        return [new \MonorepoBuilder20211101\Symplify\ComposerJsonManipulator\Bundle\ComposerJsonManipulatorBundle(), new \MonorepoBuilder20211101\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle(), new \MonorepoBuilder20211101\Symplify\ConsoleColorDiff\Bundle\ConsoleColorDiffBundle()];
    }
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    protected function build($containerBuilder) : void
    {
        $containerBuilder->addCompilerPass(new \MonorepoBuilder20211101\Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass([\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface::class]));
    }
}
