<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Config;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Webmozart\Assert\Assert;

final class MBConfig extends ContainerConfigurator
{
    private static bool $disableDefaultWorkers = false;

    private static bool $disablePackageReplace = false;

    /**
     * null = no skip rule set; empty array = skip for all packages; non-empty = skip only for matching package types.
     * @var array<int, string>|null
     */
    private static ?array $skippedAutoloadForTypes = null;

    /**
     * Same shape and semantics as $skippedAutoloadForTypes, applied to autoload-dev.
     * @var array<int, string>|null
     */
    private static ?array $skippedAutoloadDevForTypes = null;

    /**
     * @var array<class-string<ReleaseWorkerInterface>>
     */
    private static array $userWorkerClasses = [];

    /**
     * @param string[] $packageDirectories
     */
    public function packageDirectories(array $packageDirectories): void
    {
        Assert::allFileExists($packageDirectories);

        $parameters = $this->parameters();
        $parameters->set(Option::PACKAGE_DIRECTORIES, $packageDirectories);
    }

    public static function isDisableDefaultWorkers(): bool
    {
        return self::$disableDefaultWorkers;
    }

    public static function isPackageReplaceDisabled(): bool
    {
        return self::$disablePackageReplace;
    }

    /**
     * @return array<class-string<ReleaseWorkerInterface>>
     */
    public static function getUserWorkerClasses(): array
    {
        return self::$userWorkerClasses;
    }

    public static function disableDefaultWorkers(): void
    {
        self::$disableDefaultWorkers = true;
    }

    public static function disablePackageReplace(): void
    {
        self::$disablePackageReplace = true;
    }

    /**
     * Skip merging the given composer.json sections from internal packages into the root composer.json.
     *
     * Repeated calls follow last-call-wins semantics PER SECTION. Calls touching different sections compose;
     * calls touching the same section override.
     *
     * @param AutoloadSection[]|null $sections list of AutoloadSection cases. Pass null together with $forTypes=null
     *        for the deprecated zero-arg legacy form (skips both sections for all packages with a deprecation notice).
     * @param array<PackageType|string>|null $forTypes empty array means "all packages"; non-empty filters by
     *        composer.json `type`. Each element MUST be either a {@see PackageType} enum case (preferred for the
     *        four schema-documented types) or a non-empty string (escape hatch for composer/installers types like
     *        `wordpress-plugin`, `drupal-module`, `symfony-bundle` and user-defined types). Pass null together with
     *        $sections=null for the deprecated zero-arg legacy form.
     */
    public static function disableAutoloadMerge(?array $sections = null, ?array $forTypes = null): void
    {
        if ($sections === null && $forTypes === null) {
            @trigger_error(
                'Calling MBConfig::disableAutoloadMerge() with no arguments is deprecated.'
                . ' Use the parameterized form: disableAutoloadMerge(sections: [AutoloadSection::Autoload,'
                . ' AutoloadSection::AutoloadDev], forTypes: []) for the equivalent full-kill behavior.',
                E_USER_DEPRECATED,
            );
            $sections = [AutoloadSection::Autoload, AutoloadSection::AutoloadDev];
            $forTypes = [];
        } elseif ($sections === null || $forTypes === null) {
            throw new InvalidArgumentException(
                'MBConfig::disableAutoloadMerge() requires both $sections and $forTypes when either is provided.'
                . ' Use the zero-argument form only for the deprecated legacy full-kill behavior.',
            );
        }

        Assert::notEmpty($sections, '$sections must contain at least one AutoloadSection case.');

        $normalizedForTypes = self::normalizeForTypes($forTypes);

        foreach ($sections as $section) {
            // Exhaustive enum match — throws \UnhandledMatchError at runtime if a non-AutoloadSection element sneaks in
            match ($section) {
                AutoloadSection::Autoload => self::$skippedAutoloadForTypes = $normalizedForTypes,
                AutoloadSection::AutoloadDev => self::$skippedAutoloadDevForTypes = $normalizedForTypes,
            };
        }
    }

    public static function shouldSkipAutoload(?string $packageType): bool
    {
        return self::shouldSkip(self::$skippedAutoloadForTypes, $packageType);
    }

    public static function shouldSkipAutoloadDev(?string $packageType): bool
    {
        return self::shouldSkip(self::$skippedAutoloadDevForTypes, $packageType);
    }

    /**
     * @deprecated since the parameterized disableAutoloadMerge() API was introduced. Use {@see shouldSkipAutoload()}
     *             and {@see shouldSkipAutoloadDev()} for per-package decisions. This getter returns true ONLY when
     *             both autoload sections are configured to skip merging for ALL packages (matching the legacy
     *             zero-argument disableAutoloadMerge() behavior).
     */
    public static function isAutoloadMergeDisabled(): bool
    {
        return self::$skippedAutoloadForTypes === [] && self::$skippedAutoloadDevForTypes === [];
    }

    /**
     * @param string[] $packageDirectories
     */
    public function packageDirectoriesExcludes(array $packageDirectories): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::PACKAGE_DIRECTORIES_EXCLUDES, $packageDirectories);
    }

    public function defaultBranch(string $defaultBranch): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::DEFAULT_BRANCH_NAME, $defaultBranch);
    }

    /**
     * @param array<string, mixed> $dataToRemove
     */
    public function dataToRemove(array $dataToRemove): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::DATA_TO_REMOVE, $dataToRemove);
    }

    /**
     * @param array<string, mixed> $dataToAppend
     */
    public function dataToAppend(array $dataToAppend): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::DATA_TO_APPEND, $dataToAppend);
    }

    /**
     * @param array<class-string<ReleaseWorkerInterface>> $workerClasses
     */
    public function workers(array $workerClasses): void
    {
        $services = $this->services();

        self::$userWorkerClasses = $workerClasses;

        // Use a unique service ID prefix so user-registered workers don't replace
        // default definitions (which would preserve the original array position in
        // the container and break the user's intended ordering).
        foreach ($workerClasses as $index => $workerClass) {
            $services->set('user_release_worker.' . $index, $workerClass);
        }
    }

    public function packageAliasFormat(string $packageAliasFormat): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::PACKAGE_ALIAS_FORMAT, $packageAliasFormat);
    }

    /**
     * @param string[] $sectionOrder
     */
    public function composerSectionOrder(array $sectionOrder): void
    {
        $parameters = $this->parameters();
        $parameters->set(Option::SECTION_ORDER, $sectionOrder);
    }

    /**
     * Normalize a hybrid forTypes list (PackageType cases + non-empty strings) to a string[] for internal storage.
     *
     * @param array<PackageType|string> $forTypes
     * @return string[]
     */
    private static function normalizeForTypes(array $forTypes): array
    {
        $normalized = [];
        foreach ($forTypes as $forType) {
            if ($forType instanceof PackageType) {
                $normalized[] = $forType->value;
                continue;
            }

            Assert::stringNotEmpty(
                $forType,
                '$forTypes elements must be either a PackageType enum case or a non-empty string.',
            );
            $normalized[] = $forType;
        }

        return $normalized;
    }

    /**
     * Resolve whether a section should be skipped for a package, given the configured filter rule.
     *
     * Three branches keep the two filter channels distinct so callers can reason about them precisely:
     *   - null filter list = no rule was set, the section merges normally.
     *   - empty filter list = no type discrimination, every package is skipped.
     *   - non-empty filter list = strict literal match on the package type, only packages that explicitly
     *     declare a matching type in their own composer.json are skipped. Packages without an explicit
     *     type field are intentionally NOT swept up — even though Composer itself defaults a missing type
     *     to library for installation purposes, this filter requires authors to declare the type to be
     *     caught, which keeps the explicit-types channel separate from the catch-all empty-list channel.
     *
     * @param array<int, string>|null $forTypes
     */
    private static function shouldSkip(?array $forTypes, ?string $packageType): bool
    {
        if ($forTypes === null) {
            return false;
        }

        if ($forTypes === []) {
            return true;
        }

        return in_array($packageType, $forTypes, true);
    }
}
