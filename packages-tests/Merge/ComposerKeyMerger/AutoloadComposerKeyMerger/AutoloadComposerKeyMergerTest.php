<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Tests\Merge\ComposerKeyMerger\AutoloadComposerKeyMerger;

use InvalidArgumentException;
use ReflectionClass;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Config\AutoloadSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Config\PackageType;
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

        $this->resetMBConfigStaticFlags();
    }

    protected function tearDown(): void
    {
        $this->resetMBConfigStaticFlags();
    }

    public function testNoRuleSetMergesAutoloadAndAutoloadDev(): void
    {
        $packageJson = $this->makePackage('library');
        $composerJson = new ComposerJson();

        $this->autoloadComposerKeyMerger->merge($composerJson, $packageJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $packageJson);

        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $composerJson->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $composerJson->getAutoloadDev());
    }

    public function testSkipBothForAllPackagesEmptiesBothSections(): void
    {
        MBConfig::disableAutoloadMerge(
            [AutoloadSection::Autoload, AutoloadSection::AutoloadDev],
            []
        );

        // Test for library type
        $libraryJson = $this->makePackage('library');
        $mainJson1 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson1, $libraryJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson1, $libraryJson);
        $this->assertSame([], $mainJson1->getAutoload());
        $this->assertSame([], $mainJson1->getAutoloadDev());

        // Test for project type
        $projectJson = $this->makePackage('project');
        $mainJson2 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson2, $projectJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson2, $projectJson);
        $this->assertSame([], $mainJson2->getAutoload());
        $this->assertSame([], $mainJson2->getAutoloadDev());

        // Test for null type
        $nullTypeJson = $this->makePackage(null);
        $mainJson3 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson3, $nullTypeJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson3, $nullTypeJson);
        $this->assertSame([], $mainJson3->getAutoload());
        $this->assertSame([], $mainJson3->getAutoloadDev());
    }

    public function testSkipAutoloadOnlyForAllLeavesAutoloadDevMerged(): void
    {
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], []);

        $packageJson = $this->makePackage('library');
        $composerJson = new ComposerJson();

        $this->autoloadComposerKeyMerger->merge($composerJson, $packageJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $packageJson);

        $this->assertSame([], $composerJson->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $composerJson->getAutoloadDev());
    }

    public function testSkipAutoloadDevOnlyForAllLeavesAutoloadMerged(): void
    {
        MBConfig::disableAutoloadMerge([AutoloadSection::AutoloadDev], []);

        $packageJson = $this->makePackage('library');
        $composerJson = new ComposerJson();

        $this->autoloadComposerKeyMerger->merge($composerJson, $packageJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $packageJson);

        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $composerJson->getAutoload());
        $this->assertSame([], $composerJson->getAutoloadDev());
    }

    public function testSkipAutoloadForLibrariesOnlySkipsLibrary(): void
    {
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], [PackageType::Library]);

        $packageJson = $this->makePackage('library');
        $composerJson = new ComposerJson();

        $this->autoloadComposerKeyMerger->merge($composerJson, $packageJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $packageJson);

        $this->assertSame([], $composerJson->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $composerJson->getAutoloadDev());
    }

    public function testSkipAutoloadForLibrariesOnlyMergesProject(): void
    {
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], [PackageType::Library]);

        $packageJson = $this->makePackage('project');
        $composerJson = new ComposerJson();

        $this->autoloadComposerKeyMerger->merge($composerJson, $packageJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $packageJson);

        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $composerJson->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $composerJson->getAutoloadDev());
    }

    public function testSkipAutoloadForLibrariesOnlyMergesMissingType(): void
    {
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], [PackageType::Library]);

        $packageJson = $this->makePackage(null);
        $composerJson = new ComposerJson();

        $this->autoloadComposerKeyMerger->merge($composerJson, $packageJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $packageJson);

        // Strict literal match: a package that omits the `type` field does NOT match [PackageType::Library].
        // Pass `forTypes: []` to skip every package regardless of type. This keeps the two channels distinct:
        // explicit type-filters target ONLY packages that declare the matching `type` themselves.
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $composerJson->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $composerJson->getAutoloadDev());
    }

    public function testSkipAutoloadForProjectsOnlyMergesMissingType(): void
    {
        // Same strict literal rule for the inverse case: filtering by PackageType::Project does NOT match
        // untyped packages either. The only way to skip ALL packages regardless of type is `forTypes: []`.
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], [PackageType::Project]);

        $packageJson = $this->makePackage(null);
        $composerJson = new ComposerJson();

        $this->autoloadComposerKeyMerger->merge($composerJson, $packageJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $packageJson);

        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $composerJson->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $composerJson->getAutoloadDev());
    }

    public function testSkipBothForLibrariesOnlySkipsLibraryButMergesProject(): void
    {
        MBConfig::disableAutoloadMerge(
            [AutoloadSection::Autoload, AutoloadSection::AutoloadDev],
            [PackageType::Library]
        );

        // Library type - both sections skipped
        $libraryJson = $this->makePackage('library');
        $mainJson1 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson1, $libraryJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson1, $libraryJson);
        $this->assertSame([], $mainJson1->getAutoload());
        $this->assertSame([], $mainJson1->getAutoloadDev());

        // Project type - both sections merged
        $projectJson = $this->makePackage('project');
        $mainJson2 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson2, $projectJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson2, $projectJson);
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $mainJson2->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $mainJson2->getAutoloadDev());
    }

    public function testLastCallWinsForSameSection(): void
    {
        // First call: skip autoload only for libraries
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], [PackageType::Library]);

        // Second call: skip autoload for ALL packages (overrides first call for Autoload section)
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], []);

        // For a project package, the second call now wins (autoload IS skipped despite type being project)
        $projectJson = $this->makePackage('project');
        $composerJson = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($composerJson, $projectJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $projectJson);

        $this->assertSame([], $composerJson->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $composerJson->getAutoloadDev());
    }

    public function testIndependentSectionsCompose(): void
    {
        // Call 1: skip autoload for libraries
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], [PackageType::Library]);

        // Call 2: skip autoload-dev for libraries (different section, rules combine)
        MBConfig::disableAutoloadMerge([AutoloadSection::AutoloadDev], [PackageType::Library]);

        // For library: both sections skipped (rules compose)
        $libraryJson = $this->makePackage('library');
        $mainJson1 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson1, $libraryJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson1, $libraryJson);
        $this->assertSame([], $mainJson1->getAutoload());
        $this->assertSame([], $mainJson1->getAutoloadDev());

        // For project: both sections merged (type doesn't match 'library')
        $projectJson = $this->makePackage('project');
        $mainJson2 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson2, $projectJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson2, $projectJson);
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $mainJson2->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $mainJson2->getAutoloadDev());
    }

    public function testForTypesAcceptsPlainStringEscapeHatch(): void
    {
        // Hybrid API: composer/installers types (and user-defined types) MUST work as plain strings
        // even though they are not part of the four-case PackageType enum derived from the official schema.
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], ['wordpress-plugin']);

        // Package with the wordpress-plugin type → autoload skipped via the string match
        $wpPackageJson = $this->makePackage('wordpress-plugin');
        $mainJson1 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson1, $wpPackageJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson1, $wpPackageJson);
        $this->assertSame([], $mainJson1->getAutoload());
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ], $mainJson1->getAutoloadDev());

        // Library type → still merged (string filter doesn't include 'library')
        $libraryJson = $this->makePackage('library');
        $mainJson2 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson2, $libraryJson);
        $this->autoloadDevComposerKeyMerger->merge($mainJson2, $libraryJson);
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $mainJson2->getAutoload());
    }

    public function testForTypesMixesEnumAndStringInOneCall(): void
    {
        // The hybrid type accepts both enum cases and plain strings in the same array.
        MBConfig::disableAutoloadMerge(
            [AutoloadSection::Autoload],
            [PackageType::Library, 'symfony-bundle']
        );

        // Library matches via enum case
        $libraryJson = $this->makePackage('library');
        $mainJson1 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson1, $libraryJson);
        $this->assertSame([], $mainJson1->getAutoload());

        // symfony-bundle matches via plain string
        $sfBundleJson = $this->makePackage('symfony-bundle');
        $mainJson2 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson2, $sfBundleJson);
        $this->assertSame([], $mainJson2->getAutoload());

        // Project does NOT match — autoload still merges
        $projectJson = $this->makePackage('project');
        $mainJson3 = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($mainJson3, $projectJson);
        $this->assertSame([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ], $mainJson3->getAutoload());
    }

    public function testLegacyZeroArgFormStillSkipsBothSectionsForAllPackages(): void
    {
        // Back-compat: zero-arg disableAutoloadMerge() must continue to behave as a full kill switch.
        // Suppress the deprecation notice for this assertion path; the trigger itself is verified separately.
        @MBConfig::disableAutoloadMerge();

        $libraryJson = $this->makePackage('library');
        $composerJson = new ComposerJson();
        $this->autoloadComposerKeyMerger->merge($composerJson, $libraryJson);
        $this->autoloadDevComposerKeyMerger->merge($composerJson, $libraryJson);

        $this->assertSame([], $composerJson->getAutoload());
        $this->assertSame([], $composerJson->getAutoloadDev());
    }

    public function testLegacyZeroArgFormTriggersDeprecation(): void
    {
        $captured = [];
        set_error_handler(static function (int $errno, string $errstr) use (&$captured): bool {
            if ($errno === E_USER_DEPRECATED) {
                $captured[] = $errstr;
            }

            return true;
        });

        try {
            MBConfig::disableAutoloadMerge();
        } finally {
            restore_error_handler();
        }

        $this->assertCount(1, $captured);
        $this->assertStringContainsString('deprecated', $captured[0]);
        $this->assertStringContainsString('disableAutoloadMerge', $captured[0]);
    }

    public function testCallingWithOnlyOneArgumentThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('requires both $sections and $forTypes');

        /** @phpstan-ignore-next-line argument.type */
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload]);
    }

    public function testIsAutoloadMergeDisabledTrueOnlyWhenBothSectionsSkipAll(): void
    {
        // Back-compat getter: matches the legacy semantic of "the binary disable flag was set"
        $this->assertFalse(MBConfig::isAutoloadMergeDisabled(), 'fresh state — no rule set');

        // Partial skip → not "fully disabled"
        MBConfig::disableAutoloadMerge([AutoloadSection::Autoload], []);
        $this->assertFalse(MBConfig::isAutoloadMergeDisabled(), 'autoload-only skip → not full kill');

        $this->resetMBConfigStaticFlags();
        MBConfig::disableAutoloadMerge([AutoloadSection::AutoloadDev], []);
        $this->assertFalse(MBConfig::isAutoloadMergeDisabled(), 'autoload-dev-only skip → not full kill');

        // Full kill via parameterized form
        $this->resetMBConfigStaticFlags();
        MBConfig::disableAutoloadMerge(
            [AutoloadSection::Autoload, AutoloadSection::AutoloadDev],
            []
        );
        $this->assertTrue(MBConfig::isAutoloadMergeDisabled(), 'both sections + all packages → full kill');

        // Full kill via legacy zero-arg
        $this->resetMBConfigStaticFlags();
        @MBConfig::disableAutoloadMerge();
        $this->assertTrue(MBConfig::isAutoloadMergeDisabled(), 'legacy zero-arg → full kill');

        // Filter-by-type → not "fully disabled" (only some packages skipped)
        $this->resetMBConfigStaticFlags();
        MBConfig::disableAutoloadMerge(
            [AutoloadSection::Autoload, AutoloadSection::AutoloadDev],
            [PackageType::Library]
        );
        $this->assertFalse(MBConfig::isAutoloadMergeDisabled(), 'library-only filter → not full kill');
    }

    private function makePackage(?string $type): ComposerJson
    {
        $composerJson = new ComposerJson();

        // ComposerJson doesn't expose a setType() method, use reflection to set the type field
        if ($type !== null) {
            $reflectionClass = new ReflectionClass($composerJson);
            $typeProperty = $reflectionClass->getProperty('type');
            $typeProperty->setValue($composerJson, $type);
        }

        $composerJson->setAutoload([
            'psr-4' => ['Acme\\Lib\\' => 'src'],
        ]);

        $composerJson->setAutoloadDev([
            'psr-4' => ['Acme\\Lib\\Tests\\' => 'tests'],
        ]);

        return $composerJson;
    }

    private function resetMBConfigStaticFlags(): void
    {
        $reflectionClass = new ReflectionClass(MBConfig::class);

        $reflectionProperty = $reflectionClass->getProperty('skippedAutoloadForTypes');
        $reflectionProperty->setValue(null, null);

        $skippedAutoloadDevForTypes = $reflectionClass->getProperty('skippedAutoloadDevForTypes');
        $skippedAutoloadDevForTypes->setValue(null, null);
    }
}
