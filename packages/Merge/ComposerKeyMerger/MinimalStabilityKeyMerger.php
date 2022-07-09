<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder202207\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use PharIo\Version\InvalidPreReleaseSuffixException;
use PharIo\Version\PreReleaseSuffix;
final class MinimalStabilityKeyMerger implements ComposerKeyMergerInterface
{
    public function merge(ComposerJson $mainComposerJson, ComposerJson $newComposerJson) : void
    {
        try {
            $newStability = new PreReleaseSuffix((string) $newComposerJson->getMinimumStability());
        } catch (InvalidPreReleaseSuffixException $exception) {
            return;
        }
        try {
            $mainStability = new PreReleaseSuffix((string) $mainComposerJson->getMinimumStability());
        } catch (InvalidPreReleaseSuffixException $exception) {
            $mainStability = null;
        }
        if ($mainStability === null || $mainStability->isGreaterThan($newStability)) {
            $mainComposerJson->setMinimumStability($newStability->asString());
        }
    }
}
