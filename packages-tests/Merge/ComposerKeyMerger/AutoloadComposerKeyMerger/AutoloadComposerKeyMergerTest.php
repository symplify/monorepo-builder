<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\ComposerKeyMerger\AutoloadComposerKeyMerger;

use ReflectionClass;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Kernel\MonorepoBuilderKernel;
use Symplify\MonorepoBuilder\Merge\ComposerKeyMerger\AutoloadComposerKeyMerger;
use Symplify\MonorepoBuilder\Merge\ComposerKeyMerger\AutoloadDevComposerKeyMerger;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class AutoloadComposerKeyMergerTest extends AbstractKernelTestCase
{
    private AutoloadComposerKeyMerger $autoloadComposerKeyMerger;

    private AutoloadDevComposerKeyMerger $autoloadDevComposerKeyMerger;

    protected function setUp(): void
    {
        $this->bootKernel(MonorepoBuilderKernel::class);

        $this->autoloadComposerKeyMerger = $this->getService(AutoloadComposerKeyMerger::class);
        $this->autoloadDevComposerKeyMerger = $this->getService(AutoloadDevComposerKeyMerger::class);

        $this->resetDisableAutoloadMergeFlag();
    }

    protected function tearDown(): void
    {
        $this->resetDisableAutoloadMergeFlag();
    }

    public function testDefaultBehaviorMergesAutoload(): void
    {
        $newComposerJson = new ComposerJson();
        $newComposerJson->setAutoload([
            'psr-4' => ['Acme\\Internal\\' => 'src'],
        ]);

        $mainComposerJson = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainComposerJson, $newComposerJson);

        $this->assertSame([
            'psr-4' => ['Acme\\Internal\\' => 'src'],
        ], $mainComposerJson->getAutoload());
    }

    public function testDisableAutoloadMergeSkipsAutoload(): void
    {
        $newComposerJson = new ComposerJson();
        $newComposerJson->setAutoload([
            'psr-4' => ['Acme\\Internal\\' => 'src'],
        ]);

        MBConfig::disableAutoloadMerge();

        $mainComposerJson = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainComposerJson, $newComposerJson);

        $this->assertSame([], $mainComposerJson->getAutoload());
    }

    public function testDisableAutoloadMergeSkipsAutoloadDev(): void
    {
        $newComposerJson = new ComposerJson();
        $newComposerJson->setAutoloadDev([
            'psr-4' => ['Acme\\Internal\\Tests\\' => 'tests'],
        ]);

        MBConfig::disableAutoloadMerge();

        $mainComposerJson = new ComposerJson();
        $this->autoloadDevComposerKeyMerger->merge($mainComposerJson, $newComposerJson);

        $this->assertSame([], $mainComposerJson->getAutoloadDev());
    }

    private function resetDisableAutoloadMergeFlag(): void
    {
        $reflectionClass = new ReflectionClass(MBConfig::class);

        $reflectionProperty = $reflectionClass->getProperty('disableAutoloadMerge');
        $reflectionProperty->setValue(null, false);
    }
}
