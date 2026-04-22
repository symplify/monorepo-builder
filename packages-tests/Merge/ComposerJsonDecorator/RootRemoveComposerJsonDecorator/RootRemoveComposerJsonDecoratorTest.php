<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\ComposerJsonDecorator\RootRemoveComposerJsonDecorator;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Merge\ComposerJsonDecorator\RootRemoveComposerJsonDecorator;
use Symplify\MonorepoBuilder\Merge\Configuration\MergedPackagesCollector;

final class RootRemoveComposerJsonDecoratorTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resetDisablePackageReplaceFlag();
    }

    protected function tearDown(): void
    {
        $this->resetDisablePackageReplaceFlag();
    }

    public function testDefaultBehaviorRemovesMergedPackagesFromRoot(): void
    {
        $mergedPackagesCollector = new MergedPackagesCollector();
        $mergedPackagesCollector->addPackage('acme/internal-a');

        $composerJson = new ComposerJson();
        $composerJson->setRequire([
            'acme/internal-a' => '^1.0',
            'vendor/external' => '^2.0',
        ]);
        $composerJson->setRequireDev([
            'acme/internal-a' => '^1.0',
            'phpunit/phpunit' => '^10.0',
        ]);

        (new RootRemoveComposerJsonDecorator($mergedPackagesCollector))->decorate($composerJson);

        $this->assertSame(['vendor/external' => '^2.0'], $composerJson->getRequire());
        $this->assertSame(['phpunit/phpunit' => '^10.0'], $composerJson->getRequireDev());
    }

    public function testDisablePackageReplaceKeepsMergedPackagesInRoot(): void
    {
        $mergedPackagesCollector = new MergedPackagesCollector();
        $mergedPackagesCollector->addPackage('acme/internal-a');

        MBConfig::disablePackageReplace();

        $composerJson = new ComposerJson();
        $composerJson->setRequire([
            'acme/internal-a' => '^1.0',
            'vendor/external' => '^2.0',
        ]);
        $composerJson->setRequireDev([
            'acme/internal-a' => '^1.0',
            'phpunit/phpunit' => '^10.0',
        ]);

        (new RootRemoveComposerJsonDecorator($mergedPackagesCollector))->decorate($composerJson);

        $this->assertSame([
            'acme/internal-a' => '^1.0',
            'vendor/external' => '^2.0',
        ], $composerJson->getRequire());
        $this->assertSame([
            'acme/internal-a' => '^1.0',
            'phpunit/phpunit' => '^10.0',
        ], $composerJson->getRequireDev());
    }

    private function resetDisablePackageReplaceFlag(): void
    {
        $reflectionClass = new ReflectionClass(MBConfig::class);

        $reflectionProperty = $reflectionClass->getProperty('disablePackageReplace');
        $reflectionProperty->setValue(null, false);
    }
}
