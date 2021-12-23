<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Configuration;

use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class ModifyingComposerJsonProvider
{
    /**
     * @var \Symplify\ComposerJsonManipulator\ComposerJsonFactory
     */
    private $composerJsonFactory;
    /**
     * @var \Symplify\PackageBuilder\Parameter\ParameterProvider
     */
    private $parameterProvider;
    public function __construct(\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ComposerJsonFactory $composerJsonFactory, \MonorepoBuilder20211223\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->parameterProvider = $parameterProvider;
    }
    public function getRemovingComposerJson() : ?\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $dataToRemove = $this->parameterProvider->provideArrayParameter(\Symplify\MonorepoBuilder\ValueObject\Option::DATA_TO_REMOVE);
        if ($dataToRemove === []) {
            return null;
        }
        return $this->composerJsonFactory->createFromArray($dataToRemove);
    }
    public function getAppendingComposerJson() : ?\MonorepoBuilder20211223\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson
    {
        $dataToAppend = $this->parameterProvider->provideArrayParameter(\Symplify\MonorepoBuilder\ValueObject\Option::DATA_TO_APPEND);
        if ($dataToAppend === []) {
            return null;
        }
        return $this->composerJsonFactory->createFromArray($dataToAppend);
    }
}
