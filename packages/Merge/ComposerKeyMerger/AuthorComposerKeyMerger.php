<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
final class AuthorComposerKeyMerger implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    public function merge(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getAuthors() === []) {
            return;
        }
        $mainAuthors = \array_column($mainComposerJson->getAuthors(), null, 'name');
        $newAuthors = \array_column($newComposerJson->getAuthors(), null, 'name');
        $authors = \array_merge($mainAuthors, $newAuthors);
        $authors = \array_values($authors);
        $mainComposerJson->setAuthors($authors);
    }
}
