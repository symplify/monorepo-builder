<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Config;

/**
 * Composer package type filter values, derived from the official Composer schema.
 *
 * @see https://getcomposer.org/doc/04-schema.md#type
 *
 * Cases cover the four schema-documented package types. For ecosystem types defined by
 * composer/installers (e.g., `wordpress-plugin`, `drupal-module`, `symfony-bundle`) and
 * for user-defined types, callers may pass plain strings alongside enum cases — see
 * `MBConfig::disableAutoloadMerge()` for the accepted union shape.
 */
enum PackageType: string
{
    case Library = 'library';
    case Project = 'project';
    case Metapackage = 'metapackage';
    case ComposerPlugin = 'composer-plugin';
}
