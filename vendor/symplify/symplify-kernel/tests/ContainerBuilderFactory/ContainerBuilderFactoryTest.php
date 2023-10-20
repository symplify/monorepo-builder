<?php

declare (strict_types=1);
namespace MonorepoBuilderPrefix202310\Symplify\SymplifyKernel\Tests\ContainerBuilderFactory;

use MonorepoBuilderPrefix202310\PHPUnit\Framework\TestCase;
use MonorepoBuilderPrefix202310\Symplify\SmartFileSystem\SmartFileSystem;
use MonorepoBuilderPrefix202310\Symplify\SymplifyKernel\Config\Loader\ParameterMergingLoaderFactory;
use MonorepoBuilderPrefix202310\Symplify\SymplifyKernel\ContainerBuilderFactory;
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
