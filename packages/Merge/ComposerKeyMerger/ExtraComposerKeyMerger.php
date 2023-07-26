<?php

declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;

final class ExtraComposerKeyMerger implements ComposerKeyMergerInterface
{
    /**
     * @var string
     */
    private const PHPSTAN = 'phpstan';

    public function __construct(
        private SortedParameterMerger $sorted_parameter_merger
    ) {
    }

    public function merge(ComposerJson $mainComposerJson, ComposerJson $newComposerJson): void
    {
        if ($newComposerJson->getExtra() === []) {
            return;
        }

        // clean content not desired to merge
        $newComposerJsonExtra = $newComposerJson->getExtra();
        // part of the plugin only
        if (isset($newComposerJsonExtra[self::PHPSTAN]['includes'])) {
            unset($newComposerJsonExtra[self::PHPSTAN]['includes']);

            if ($newComposerJsonExtra[self::PHPSTAN] === []) {
                unset($newComposerJsonExtra[self::PHPSTAN]);
            }
        }

        $extra = $this->sorted_parameter_merger->mergeRecursiveAndSort($mainComposerJson->getExtra(), $newComposerJsonExtra);

        // do not merge extra alias as only for local packages
        if (isset($extra['branch-alias'])) {
            unset($extra['branch-alias']);
        }

        if (! is_array($extra)) {
            return;
        }

        $mainComposerJson->setExtra($extra);
    }
}
