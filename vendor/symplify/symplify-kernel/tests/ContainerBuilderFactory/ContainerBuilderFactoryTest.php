<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202308\Symplify\SymplifyKernel\Tests\ContainerBuilderFactory;

use MonorepoBuilderPrefix202308\PHPUnit\Framework\TestCase;
use MonorepoBuilderPrefix202308\Symplify\SmartFileSystem\SmartFileSystem;
use MonorepoBuilderPrefix202308\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilderPrefix202308\Symplify\SymplifyKernel\ContainerBuilderFactory;
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
