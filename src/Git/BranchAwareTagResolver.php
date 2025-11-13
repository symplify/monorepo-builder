<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Git;

use Exception;
use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

/**
 * Resolves the most recent tag that is reachable from the current branch
 * and optionally matches the same major version prefix.
 *
 * This resolver is branch-aware and supports LTS release strategies where
 * multiple version lines (e.g., 2.x and 3.x) can coexist on different branches.
 *
 * @api used by autowire when branch-aware tag resolution is enabled
 */
final readonly class BranchAwareTagResolver implements TagResolverInterface
{
    use TagParserTrait;

    public function __construct(
        private ProcessRunner $processRunner
    ) {
    }

    /**
     * Returns the most recent tag reachable from the current branch.
     * Returns null when there are no tags reachable from current branch.
     */
    public function resolve(string $gitDirectory): ?string
    {
        // Get all tags reachable from current branch (HEAD), sorted by version
        $command = ['git', 'tag', '--merged', 'HEAD', '--sort=-version:refname'];
        $tagList = $this->parseTags($this->processRunner->run($command, $gitDirectory));

        if ($tagList === []) {
            return null;
        }

        // Return the highest version tag that is reachable from current branch
        return $tagList[0];
    }

    /**
     * Resolves the most recent tag with the same major version as the provided version.
     * This is useful for LTS scenarios where you want to compare within the same version line.
     *
     * For example:
     * - If new version is 2.1.5, it finds the latest 2.x.x tag
     * - If new version is 3.0.0, it finds the latest 3.x.x tag (or null if none exists)
     *
     * @return string|null The most recent tag with matching major version, or null if none found
     */
    public function resolveForVersion(string $gitDirectory, Version $version): ?string
    {
        // Get all tags reachable from current branch, sorted by version descending
        $command = ['git', 'tag', '--merged', 'HEAD', '--sort=-version:refname'];
        $tagList = $this->parseTags($this->processRunner->run($command, $gitDirectory));

        if ($tagList === []) {
            return null;
        }

        $majorVersion = $version->getMajor()->getValue();

        // Find the first tag that matches the same major version
        foreach ($tagList as $tag) {
            try {
                // Normalize tag (remove 'v' prefix if present)
                $normalizedTag = $this->normalizeTagName($tag);
                $tagVersion = new Version($normalizedTag);

                // Check if this tag has the same major version
                if ($tagVersion->getMajor()->getValue() === $majorVersion) {
                    return $tag;
                }
            } catch (Exception) {
                // Skip invalid version tags
                continue;
            }
        }

        // No tag found with matching major version
        return null;
    }
}
