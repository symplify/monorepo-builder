<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use Symplify\MonorepoBuilder\Merge\Validation\AutoloadPathValidator;
final class AutoloadComposerKeyMerger implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Validation\AutoloadPathValidator
     */
    private $autoloadPathValidator;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger
     */
    private $sortedParameterMerger;
    public function __construct(\Symplify\MonorepoBuilder\Merge\Validation\AutoloadPathValidator $autoloadPathValidator, \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger $sortedParameterMerger)
    {
        $this->autoloadPathValidator = $autoloadPathValidator;
        $this->sortedParameterMerger = $sortedParameterMerger;
    }
    public function merge(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getAutoload() === []) {
            return;
        }
        $this->autoloadPathValidator->ensureAutoloadPathExists($newComposerJson);
        $autoload = $this->sortedParameterMerger->mergeRecursiveAndSort($mainComposerJson->getAutoload(), $newComposerJson->getAutoload());
        $mainComposerJson->setAutoload($autoload);
    }
}
