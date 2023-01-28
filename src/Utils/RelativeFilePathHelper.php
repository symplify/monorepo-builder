<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Utils;

use Symfony\Component\Filesystem\Filesystem;

final class RelativeFilePathHelper
{
    public static function resolveFromCwd(string $filePath): string
    {
        $filesystem = new Filesystem();
        $relativeFilePathFromCwd = $filesystem->makePathRelative((string) \realpath($filePath), \getcwd());
        return \rtrim($relativeFilePathFromCwd, '/');
    }
}
