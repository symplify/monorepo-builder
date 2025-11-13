<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Git;

/**
 * Shared trait for parsing and normalizing git tags.
 * Provides common functionality for tag resolution across different strategies.
 */
trait TagParserTrait
{
    /**
     * Parses git tag command output into an array of tag names.
     * Handles cross-platform line endings (Windows \r\n vs Unix \n).
     *
     * @return string[]
     */
    private function parseTags(string $commandResult): array
    {
        $tags = trim($commandResult);

        if ($tags === '') {
            return [];
        }

        // Remove all "\r" chars in case the CLI env like the Windows OS.
        // Otherwise (ConEmu, git bash, mingw cli, e.g.), leave as is.
        $normalizedTags = str_replace("\r", '', $tags);

        $tagArray = explode("\n", $normalizedTags);

        // Filter out empty strings
        return array_filter($tagArray, static fn (string $tag): bool => $tag !== '');
    }

    /**
     * Normalizes a tag name by removing the 'v' prefix and converting to lowercase.
     * This allows for consistent version comparison regardless of tag naming convention.
     *
     * Examples:
     * - "v1.2.3" -> "1.2.3"
     * - "V2.0.0" -> "2.0.0"
     * - "1.5.0" -> "1.5.0"
     */
    private function normalizeTagName(string $tag): string
    {
        return ltrim(strtolower($tag), 'v');
    }
}
