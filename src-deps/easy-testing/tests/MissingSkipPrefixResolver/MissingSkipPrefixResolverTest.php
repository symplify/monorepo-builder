<?php

declare(strict_types=1);

namespace Symplify\EasyTesting\Tests\MissingSkipPrefixResolver;

use Symplify\EasyTesting\Finder\FixtureFinder;
use Symplify\EasyTesting\Kernel\EasyTestingKernel;
use Symplify\EasyTesting\MissplacedSkipPrefixResolver;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class MissingSkipPrefixResolverTest extends AbstractKernelTestCase
{
    private MissplacedSkipPrefixResolver $missplacedSkipPrefixResolver;

    private FixtureFinder $fixtureFinder;

    protected function setUp(): void
    {
        $this->bootKernel(EasyTestingKernel::class);
        $this->missplacedSkipPrefixResolver = $this->getService(MissplacedSkipPrefixResolver::class);
        $this->fixtureFinder = $this->getService(FixtureFinder::class);
    }

    public function test(): void
    {
        $fileInfos = $this->fixtureFinder->find([__DIR__ . '/Fixture']);
        $incorrectAndMissingSkips = $this->missplacedSkipPrefixResolver->resolve($fileInfos);

        $this->assertSame(2, $incorrectAndMissingSkips->getFileCount());
    }
}
