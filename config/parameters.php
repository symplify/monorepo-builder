<?php

declare (strict_types=1);
namespace MonorepoBuilder20210913;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set('env(GITHUB_TOKEN)', null);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::GITHUB_TOKEN, '%env(GITHUB_TOKEN)%');
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES, [\getcwd() . '/packages']);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES_EXCLUDES, []);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::DATA_TO_APPEND, []);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::DATA_TO_REMOVE, []);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::EXCLUDE_PACKAGE_VERSION_CONFLICTS, []);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::IS_STAGE_REQUIRED, \false);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::STAGES_TO_ALLOW_EXISTING_TAG, []);
    // for back compatibility, better switch to "main"
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::DEFAULT_BRANCH_NAME, 'master');
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::ROOT_DIRECTORY, \getcwd());
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_ALIAS_FORMAT, '<major>.<minor>-dev');
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::INLINE_SECTIONS, ['keywords']);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::SECTION_ORDER, [\MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::NAME, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::TYPE, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::DESCRIPTION, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::KEYWORDS, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::HOMEPAGE, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::LICENSE, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTHORS, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::BIN, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE_DEV, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD_DEV, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPOSITORIES, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PROVIDES, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFLICT, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPLACE, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS_DESCRIPTIONS, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SUGGEST, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFIG, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::MINIMUM_STABILITY, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PREFER_STABLE, \MonorepoBuilder20210913\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::EXTRA]);
};
