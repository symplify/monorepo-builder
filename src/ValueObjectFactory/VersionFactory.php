<?php

declare (strict_types=1);
namespace MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObjectFactory;

use MonorepoBuilder20210703\PharIo\Version\Version;
final class VersionFactory
{
    public function create(string $version) : \MonorepoBuilder20210703\PharIo\Version\Version
    {
        return new \MonorepoBuilder20210703\PharIo\Version\Version($version);
    }
}
