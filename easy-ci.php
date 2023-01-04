<?php

declare(strict_types=1);

use Symplify\EasyCI\Config\EasyCIConfig;

return static function (EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->typesToSkip([
        \Symplify\MonorepoBuilder\Config\MBConfig::class,
        \Symplify\MonorepoBuilder\ConflictingUpdater::class,
        \Symplify\MonorepoBuilder\Exception\Git\InvalidGitVersionException::class,
        \Symplify\MonorepoBuilder\Exception\MissingComposerJsonException::class,
        \Symplify\MonorepoBuilder\Git\MostRecentTagResolver::class,
        \Symplify\MonorepoBuilder\Package\PackageNamesProvider::class,
    ]);
};
