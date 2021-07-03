<?php

declare (strict_types=1);
namespace MonorepoBuilder20210703\Symplify\MonorepoBuilder\Validator;

use MonorepoBuilder20210703\Symplify\MonorepoBuilder\Exception\Validator\InvalidComposerJsonSetupException;
use MonorepoBuilder20210703\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option;
use MonorepoBuilder20210703\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class SourcesPresenceValidator
{
    /**
     * @var string[]
     */
    private $packageDirectories = [];
    /**
     * @var \Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider
     */
    private $composerJsonProvider;
    public function __construct(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider $composerJsonProvider, \MonorepoBuilder20210703\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->packageDirectories = $parameterProvider->provideArrayParameter(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES);
    }
    public function validatePackageComposerJsons() : void
    {
        $composerPackageFiles = $this->composerJsonProvider->getPackagesComposerFileInfos();
        if ($composerPackageFiles !== []) {
            return;
        }
        throw new \MonorepoBuilder20210703\Symplify\MonorepoBuilder\Exception\Validator\InvalidComposerJsonSetupException(\sprintf('No package "composer.json" was found in package directories: "%s". Add "composer.json" or configure another directory in "parameters > package_directories"', \implode('", "', $this->packageDirectories)));
    }
    public function validateRootComposerJsonName() : void
    {
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
        if ($rootComposerJson->getName() !== null) {
            return;
        }
        throw new \MonorepoBuilder20210703\Symplify\MonorepoBuilder\Exception\Validator\InvalidComposerJsonSetupException('Complete "name" to your root "composer.json".');
    }
}
