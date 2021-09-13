<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\ComposerKeyMerger;

use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\Merge\Arrays\SortedParameterMerger;
use Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface;
use Symplify\MonorepoBuilder\Merge\Validation\AutoloadPathValidator;
final class AutoloadDevComposerKeyMerger implements \Symplify\MonorepoBuilder\Merge\Contract\ComposerKeyMergerInterface
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
    /**
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $mainComposerJson
     * @param \Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $newComposerJson
     */
    public function merge($mainComposerJson, $newComposerJson) : void
    {
        if ($newComposerJson->getAutoloadDev() === []) {
            return;
        }
        $this->autoloadPathValidator->ensureAutoloadPathExists($newComposerJson);
        $autoloadDev = $this->sortedParameterMerger->mergeRecursiveAndSort($mainComposerJson->getAutoloadDev(), $newComposerJson->getAutoloadDev());
        $mainComposerJson->setAutoloadDev($autoloadDev);
    }
}
