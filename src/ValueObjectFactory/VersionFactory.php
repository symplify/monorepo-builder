<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\ValueObjectFactory;

use MonorepoBuilder20210707\PharIo\Version\Version;
final class VersionFactory
{
    public function create(string $version) : \MonorepoBuilder20210707\PharIo\Version\Version
    {
        return new \MonorepoBuilder20210707\PharIo\Version\Version($version);
    }
}
