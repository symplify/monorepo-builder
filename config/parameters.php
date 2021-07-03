<?php

declare (strict_types=1);
namespace MonorepoBuilder20210703;

use MonorepoBuilder20210703\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option;
return static function (\MonorepoBuilder20210703\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set('env(GITHUB_TOKEN)', null);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::GITHUB_TOKEN, '%env(GITHUB_TOKEN)%');
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES, [\getcwd() . '/packages']);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES_EXCLUDES, []);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::DATA_TO_APPEND, []);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::DATA_TO_REMOVE, []);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::EXCLUDE_PACKAGE_VERSION_CONFLICTS, []);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::IS_STAGE_REQUIRED, \false);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::STAGES_TO_ALLOW_EXISTING_TAG, []);
    // for back compatibility, better switch to "main"
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::DEFAULT_BRANCH_NAME, 'master');
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::ROOT_DIRECTORY, \getcwd());
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_ALIAS_FORMAT, '<major>.<minor>-dev');
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::INLINE_SECTIONS, ['keywords']);
    $parameters->set(\MonorepoBuilder20210703\Symplify\MonorepoBuilder\ValueObject\Option::SECTION_ORDER, [\MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::NAME, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::TYPE, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::DESCRIPTION, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::KEYWORDS, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::HOMEPAGE, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::LICENSE, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTHORS, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::BIN, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE_DEV, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD_DEV, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPOSITORIES, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PROVIDES, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFLICT, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPLACE, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS_DESCRIPTIONS, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SUGGESTS, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFIG, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::MINIMUM_STABILITY, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PREFER_STABLE, \MonorepoBuilder20210703\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::EXTRA]);
};
