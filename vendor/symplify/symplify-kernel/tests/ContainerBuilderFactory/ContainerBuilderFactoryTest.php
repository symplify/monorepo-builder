<?php

declare (strict_types=1);
namespace MonorepoBuilder20220317\Symplify\SymplifyKernel\Tests\ContainerBuilderFactory;

use MonorepoBuilder20220317\PHPUnit\Framework\TestCase;
use MonorepoBuilder20220317\Symplify\SmartFileSystem\SmartFileSystem;
use MonorepoBuilder20220317\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilder20220317\Symplify\SymplifyKernel\ContainerBuilderFactory;
final class ContainerBuilderFactoryTest extends \MonorepoBuilder20220317\PHPUnit\Framework\TestCase
{
    public function test() : void
    {
        $containerBuilderFactory = new \MonorepoBuilder20220317\Symplify\SymplifyKernel\ContainerBuilderFactory(new \MonorepoBuilder20220317\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory());
        $container = $containerBuilderFactory->create([__DIR__ . '/config/some_services.php'], [], []);
        $hasSmartFileSystemService = $container->has(\MonorepoBuilder20220317\Symplify\SmartFileSystem\SmartFileSystem::class);
        $this->assertTrue($hasSmartFileSystemService);
    }
}
