<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Git;

use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
/**
 * @api used by default autowire
 */
final class MostRecentTagResolver implements TagResolverInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Release\Process\ProcessRunner
     */
    private $processRunner;
    /**
     * @var string[]
     */
    private const COMMAND = ['git', 'tag', '-l', '--sort=committerdate'];
    public function __construct(ProcessRunner $processRunner)
    {
        $this->processRunner = $processRunner;
    }
    /**
     * Returns null, when there are no local tags yet
     */
    public function resolve(string $gitDirectory) : ?string
    {
        $tagList = $this->parseTags($this->processRunner->run(self::COMMAND, $gitDirectory));
        /** @var string $theMostRecentTag */
        $theMostRecentTag = (string) \array_pop($tagList);
        if ($theMostRecentTag === '') {
            return null;
        }
        return $theMostRecentTag;
    }
    /**
     * @return string[]
     */
    private function parseTags(string $commandResult) : array
    {
        $tags = \trim($commandResult);
        // Remove all "\r" chars in case the CLI env like the Windows OS.
        // Otherwise (ConEmu, git bash, mingw cli, e.g.), leave as is.
        $normalizedTags = \str_replace("\r", '', $tags);
        return \explode("\n", $normalizedTags);
    }
}
