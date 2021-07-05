<?php

declare (strict_types=1);
namespace MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Validation\AutoloadPathValidator;
final class AutoloadDevComposerKeyMerger implements \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
{
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Validation\AutoloadPathValidator
     */
    private $autoloadPathValidator;
    /**
     * @var \Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger
     */
    private $sortedParameterMerger;
    public function __construct(\MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Validation\AutoloadPathValidator $autoloadPathValidator, \MonorepoBuilder20210705\Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger $sortedParameterMerger)
    {
        $this->autoloadPathValidator = $autoloadPathValidator;
        $this->sortedParameterMerger = $sortedParameterMerger;
    }
    public function merge(\MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson, \MonorepoBuilder20210705\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson) : void
    {
        if ($newComposerJson->getAutoloadDev() === []) {
            return;
        }
        $this->autoloadPathValidator->ensureAutoloadPathExists($newComposerJson);
        $autoloadDev = $this->sortedParameterMerger->mergeRecursiveAndSort($mainComposerJson->getAutoloadDev(), $newComposerJson->getAutoloadDev());
        $mainComposerJson->setAutoloadDev($autoloadDev);
    }
}
