<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Git;

use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

/**
 * @api used by default autowire
 */
final readonly class MostRecentTagResolver implements TagResolverInterface
{
    use TagParserTrait;

    /**
     * @var string[]
     */
    private const COMMAND = ['git', 'tag', '-l', '--sort=committerdate'];

    public function __construct(
        private ProcessRunner $processRunner
    ) {
    }

    /**
     * Returns null, when there are no local tags yet
     */
    public function resolve(string $gitDirectory): ?string
    {
        $tagList = $this->parseTags($this->processRunner->run(self::COMMAND, $gitDirectory));

        $theMostRecentTag = (string) array_pop($tagList);

        if ($theMostRecentTag === '') {
            return null;
        }

        return $theMostRecentTag;
    }
}
