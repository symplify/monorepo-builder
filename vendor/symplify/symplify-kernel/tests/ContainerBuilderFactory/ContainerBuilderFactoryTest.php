<?php

declare (strict_types=1);
namespace MonorepoBuilder202211\Symplify\SymplifyKernel\Tests\ContainerBuilderFactory;

use MonorepoBuilder202211\PHPUnit\Framework\TestCase;
use MonorepoBuilder202211\Symplify\SmartFileSystem\SmartFileSystem;
use MonorepoBuilder202211\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilder202211\Symplify\SymplifyKernel\ContainerBuilderFactory;
final class ContainerBuilderFactoryTest extends TestCase
{
    public function test() : void
    {
        $containerBuilderFactory = new ContainerBuilderFactory(new ParameterMergingLoaderFactory());
        $containerBuilder = $containerBuilderFactory->create([__DIR__ . '/config/some_services.php'], [], []);
        $hasSmartFileSystemService = $containerBuilder->has(SmartFileSystem::class);
        $this->assertTrue($hasSmartFileSystemService);
    }
}
