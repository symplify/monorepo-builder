<?php

declare (strict_types=1);
namespace MonorepoBuilder20210703\Symplify\MonorepoBuilder\HttpKernel;

use MonorepoBuilder20210703\Symfony\Component\Config\Loader\LoaderInterface;
use MonorepoBuilder20210703\Symfony\Component\DependencyInjection\ContainerBuilder;
use MonorepoBuilder20210703\Symfony\Component\HttpKernel\Bundle\BundleInterface;
use MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\Bundle\ComposerJsonManipulatorBundle;
use MonorepoBuilder20210703\Symplify\ConsoleColorDiff\Bundle\ConsoleColorDiffBundle;
use MonorepoBuilder20210703\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use MonorepoBuilder20210703\Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass;
use MonorepoBuilder20210703\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle;
use MonorepoBuilder20210703\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class MonorepoBuilderKernel extends \MonorepoBuilder20210703\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    public function registerContainerConfiguration(\MonorepoBuilder20210703\Symfony\Component\Config\Loader\LoaderInterface $loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
        parent::registerContainerConfiguration($loader);
    }
    /**
     * @return BundleInterface[]
     */
    public function registerBundles() : iterable
    {
        return [new \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\Bundle\ComposerJsonManipulatorBundle(), new \MonorepoBuilder20210703\Symplify\SymplifyKernel\Bundle\SymplifyKernelBundle(), new \MonorepoBuilder20210703\Symplify\ConsoleColorDiff\Bundle\ConsoleColorDiffBundle()];
    }
    protected function build(\MonorepoBuilder20210703\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $containerBuilder->addCompilerPass(new \MonorepoBuilder20210703\Symplify\PackageBuilder\DependencyInjection\CompilerPass\AutowireInterfacesCompilerPass([\MonorepoBuilder20210703\Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface::class]));
    }
}
