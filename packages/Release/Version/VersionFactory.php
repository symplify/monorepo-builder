<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Version;

use MonorepoBuilder20210705\PharIo\Version\Version;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Git\MostRecentTagResolver;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\SemVersion;
final class VersionFactory
{
    /**
     * @var \Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard
     */
    private $releaseGuard;
    /**
     * @var \Symplify\MonorepoBuilder\Git\MostRecentTagResolver
     */
    private $mostRecentTagResolver;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard $releaseGuard, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Git\MostRecentTagResolver $mostRecentTagResolver)
    {
        $this->releaseGuard = $releaseGuard;
        $this->mostRecentTagResolver = $mostRecentTagResolver;
    }
    public function createValidVersion(string $versionArgument, string $stage) : \MonorepoBuilder20210705\PharIo\Version\Version
    {
        // normalize to workaround phar-io bug
        $versionArgument = \strtolower($versionArgument);
        if (\in_array($versionArgument, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::ALL, \true)) {
            return $this->resolveNextVersionByVersionKind($versionArgument);
        }
        // this object performs validation of version
        $version = new \MonorepoBuilder20210705\PharIo\Version\Version($versionArgument);
        $this->releaseGuard->guardVersion($version, $stage);
        return $version;
    }
    private function resolveNextVersionByVersionKind(string $versionKind) : \MonorepoBuilder20210705\PharIo\Version\Version
    {
        // get current version
        $mostRecentVersion = $this->mostRecentTagResolver->resolve(\getcwd());
        if ($mostRecentVersion === null) {
            // the very first tag
            return new \MonorepoBuilder20210705\PharIo\Version\Version('v0.1.0');
        }
        $mostRecentVersion = new \MonorepoBuilder20210705\PharIo\Version\Version($mostRecentVersion);
        $value = $mostRecentVersion->getMajor()->getValue();
        $currentMinorVersion = $mostRecentVersion->getMinor()->getValue();
        $currentPatchVersion = $mostRecentVersion->getPatch()->getValue();
        if ($versionKind === \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::MAJOR) {
            ++$value;
            $currentMinorVersion = 0;
            $currentPatchVersion = 0;
        }
        if ($versionKind === \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::MINOR) {
            ++$currentMinorVersion;
            $currentPatchVersion = 0;
        }
        if ($versionKind === \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::PATCH) {
            ++$currentPatchVersion;
        }
        return new \MonorepoBuilder20210705\PharIo\Version\Version(\sprintf('%d.%d.%d', $value, $currentMinorVersion, $currentPatchVersion));
    }
}
