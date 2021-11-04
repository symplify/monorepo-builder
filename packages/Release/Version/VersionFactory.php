<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Release\Version;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard;
use Symplify\MonorepoBuilder\Release\ValueObject\SemVersion;
final class VersionFactory
{
    /**
     * @var \Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard
     */
    private $releaseGuard;
    /**
     * @var \Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface
     */
    private $tagResolver;
    public function __construct(\Symplify\MonorepoBuilder\Release\Guard\ReleaseGuard $releaseGuard, \Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface $tagResolver)
    {
        $this->releaseGuard = $releaseGuard;
        $this->tagResolver = $tagResolver;
    }
    public function createValidVersion(string $versionArgument, string $stage) : \PharIo\Version\Version
    {
        // normalize to workaround phar-io bug
        $versionArgument = \strtolower($versionArgument);
        if (\in_array($versionArgument, \Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::ALL, \true)) {
            return $this->resolveNextVersionByVersionKind($versionArgument);
        }
        // this object performs validation of version
        $version = new \PharIo\Version\Version($versionArgument);
        $this->releaseGuard->guardVersion($version, $stage);
        return $version;
    }
    private function resolveNextVersionByVersionKind(string $versionKind) : \PharIo\Version\Version
    {
        // get current version
        $mostRecentVersion = $this->tagResolver->resolve(\getcwd());
        if ($mostRecentVersion === null) {
            // the very first tag
            return new \PharIo\Version\Version('v0.1.0');
        }
        $mostRecentVersion = new \PharIo\Version\Version($mostRecentVersion);
        $value = $mostRecentVersion->getMajor()->getValue();
        $currentMinorVersion = $mostRecentVersion->getMinor()->getValue();
        $currentPatchVersion = $mostRecentVersion->getPatch()->getValue();
        if ($versionKind === \Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::MAJOR) {
            ++$value;
            $currentMinorVersion = 0;
            $currentPatchVersion = 0;
        }
        if ($versionKind === \Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::MINOR) {
            ++$currentMinorVersion;
            $currentPatchVersion = 0;
        }
        if ($versionKind === \Symplify\MonorepoBuilder\Release\ValueObject\SemVersion::PATCH) {
            ++$currentPatchVersion;
        }
        return new \PharIo\Version\Version(\sprintf('%d.%d.%d', $value, $currentMinorVersion, $currentPatchVersion));
    }
}
