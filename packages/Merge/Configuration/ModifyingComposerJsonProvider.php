<?php

declare (strict_types=1);
namespace Symplify\MonorepoBuilder\Merge\Configuration;

use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ComposerJsonFactory;
use MonorepoBuilder202209\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder202209\Symplify\PackageBuilder\Parameter\ParameterProvider;
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
    public function __construct(ComposerJsonFactory $composerJsonFactory, ParameterProvider $parameterProvider)
    {
        $this->composerJsonFactory = $composerJsonFactory;
        $this->parameterProvider = $parameterProvider;
    }
    public function getRemovingComposerJson() : ?ComposerJson
    {
        $dataToRemove = $this->parameterProvider->provideArrayParameter(Option::DATA_TO_REMOVE);
        if ($dataToRemove === []) {
            return null;
        }
        return $this->composerJsonFactory->createFromArray($dataToRemove);
    }
    public function getAppendingComposerJson() : ?ComposerJson
    {
        $dataToAppend = $this->parameterProvider->provideArrayParameter(Option::DATA_TO_APPEND);
        if ($dataToAppend === []) {
            return null;
        }
        return $this->composerJsonFactory->createFromArray($dataToAppend);
    }
}
