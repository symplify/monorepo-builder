<?php

declare (strict_types=1);
namespace MonorepoBuilder20220523\Symplify\SymplifyKernel\Tests\ContainerBuilderFactory;

use MonorepoBuilder20220523\PHPUnit\Framework\TestCase;
use MonorepoBuilder20220523\Symplify\SmartFileSystem\SmartFileSystem;
use MonorepoBuilder20220523\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilder20220523\Symplify\SymplifyKernel\ContainerBuilderFactory;
final class ContainerBuilderFactoryTest extends \MonorepoBuilder20220523\PHPUnit\Framework\TestCase
{
    public function test() : void
    {
        $containerBuilderFactory = new \MonorepoBuilder20220523\Symplify\SymplifyKernel\ContainerBuilderFactory(new \MonorepoBuilder20220523\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory());
        $containerBuilder = $containerBuilderFactory->create([__DIR__ . '/config/some_services.php'], [], []);
        $hasSmartFileSystemService = $containerBuilder->has(\MonorepoBuilder20220523\Symplify\SmartFileSystem\SmartFileSystem::class);
        $this->assertTrue($hasSmartFileSystemService);
    }
}
