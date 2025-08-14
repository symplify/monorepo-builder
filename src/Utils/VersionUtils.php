<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Utils;

use PharIo\Version\Version;
use RuntimeException;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * @see \Symplify\MonorepoBuilder\Tests\Utils\VersionUtilsTest
 */
final readonly class VersionUtils
{
    private string $packageAliasFormat;

    public function __construct(ParameterProvider $parameterProvider)
    {
        $this->packageAliasFormat = $parameterProvider->provideStringParameter(Option::PACKAGE_ALIAS_FORMAT);
    }

    public function getNextAliasFormat(Version | string $version): string
    {
        $version = $this->normalizeVersion($version);

        $major = $version->getMajor()->getValue();

        $minor = $this->getNextMinorNumber($version);


        if ($major === null) {
            throw new RuntimeException('Major version cannot is null');
        }

        return str_replace(
            ['<major>', '<minor>'],
            [(string) $major, (string) $minor],
            $this->packageAliasFormat
        );
    }

    public function getRequiredNextFormat(Version | string $version): string
    {
        $version = $this->normalizeVersion($version);
        $minor = $this->getNextMinorNumber($version);

        return '^' . $version->getMajor()->getValue() . '.' . $minor;
    }

    public function getRequiredFormat(Version | string $version): string
    {
        $version = $this->normalizeVersion($version);

        $requireVersion = '^' . $version->getMajor()->getValue() . '.' . $version->getMinor()->getValue();

        $value = $version->getPatch()
            ->getValue();
        if ($value > 0) {
            $requireVersion .= '.' . $value;
        }

        return $requireVersion;
    }

    private function normalizeVersion(Version | string $version): Version
    {
        if (is_string($version)) {
            return new Version($version);
        }

        return $version;
    }

    private function getNextMinorNumber(Version $version): int
    {
        if ($version->hasPreReleaseSuffix()) {
            return (int) $version->getMinor()
                ->getValue();
        }

        return $version->getMinor()
            ->getValue() + 1;
    }
}
