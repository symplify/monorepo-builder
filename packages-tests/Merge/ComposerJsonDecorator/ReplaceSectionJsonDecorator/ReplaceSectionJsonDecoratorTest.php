<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\ReplaceSectionJsonDecorator;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\ReplaceSectionJsonDecorator;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;

final class ReplaceSectionJsonDecoratorTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetDisablePackageReplaceFlag();
    }

    protected function tearDown(): void
    {
        $this->resetDisablePackageReplaceFlag();
    }

    public function testDefaultBehaviorPopulatesReplaceSection(): void
    {
        $mergedPackagesCollector = new MergedPackagesCollector();
        $mergedPackagesCollector->addPackage('acme/internal-a');
        $mergedPackagesCollector->addPackage('acme/internal-b');

        $replaceSectionJsonDecorator = new ReplaceSectionJsonDecorator($mergedPackagesCollector);

        $composerJson = new ComposerJson();
        $replaceSectionJsonDecorator->decorate($composerJson);

        $this->assertSame([
            'acme/internal-a' => 'self.version',
            'acme/internal-b' => 'self.version',
        ], $composerJson->getReplace());
    }

    public function testDisablePackageReplaceSkipsReplaceSection(): void
    {
        $mergedPackagesCollector = new MergedPackagesCollector();
        $mergedPackagesCollector->addPackage('acme/internal-a');
        $mergedPackagesCollector->addPackage('acme/internal-b');

        MBConfig::disablePackageReplace();

        $replaceSectionJsonDecorator = new ReplaceSectionJsonDecorator($mergedPackagesCollector);

        $composerJson = new ComposerJson();
        $replaceSectionJsonDecorator->decorate($composerJson);

        $this->assertSame([], $composerJson->getReplace());
    }

    private function resetDisablePackageReplaceFlag(): void
    {
        $reflectionClass = new ReflectionClass(MBConfig::class);

        $reflectionProperty = $reflectionClass->getProperty('disablePackageReplace');
        $reflectionProperty->setValue(null, false);
    }
}
