<?php

declare (strict_types=1);
namespace MonorepoBuilder20220227;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set('env(GITHUB_TOKEN)', null);
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::GITHUB_TOKEN, '%env(GITHUB_TOKEN)%');
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::PACKAGE_DIRECTORIES, []);
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
    $parameters->set(\Symplify\MonorepoBuilder\ValueObject\Option::SECTION_ORDER, [\MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::NAME, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::TYPE, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::DESCRIPTION, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::KEYWORDS, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::HOMEPAGE, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::LICENSE, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTHORS, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::BIN, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REQUIRE_DEV, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD_DEV, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPOSITORIES, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PROVIDES, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFLICT, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::REPLACE, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SCRIPTS_DESCRIPTIONS, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::SUGGEST, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::CONFIG, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::MINIMUM_STABILITY, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::PREFER_STABLE, \MonorepoBuilder20220227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::EXTRA]);
};
