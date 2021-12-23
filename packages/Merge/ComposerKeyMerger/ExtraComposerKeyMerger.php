<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Yaml\ParametersMerger;
final class ExtraComposerKeyMerger implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @var string
     */
    private const PHPSTAN = 'phpstan';
    /**
     * @var \Symplify\PackageBuilder\Yaml\ParametersMerger
     */
    private $parametersMerger;
    public function __construct(\MonorepoBuilder20211223\Symplify\PackageBuilder\Yaml\ParametersMerger $parametersMerger)
    {
        $this->parametersMerger = $parametersMerger;
    }
    public function merge(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
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
        $extra = $this->parametersMerger->mergeWithCombine($mainComposerJson->getExtra(), $newComposerJsonExtra);
        // do not merge extra alias as only for local packages
        if (isset($extra['branch-alias'])) {
            unset($extra['branch-alias']);
        }
        if (!\is_array($extra)) {
            return;
        }
        $mainComposerJson->setExtra($extra);
    }
}
