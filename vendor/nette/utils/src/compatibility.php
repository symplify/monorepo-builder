<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace MonorepoBuilder20210801\Nette\Utils;

use MonorepoBuilder20210801\Nette;
if (\false) {
    /** @deprecated use Nette\HtmlStringable */
    interface IHtmlString extends \MonorepoBuilder20210801\Nette\HtmlStringable
    {
    }
} elseif (!\interface_exists(\MonorepoBuilder20210801\Nette\Utils\IHtmlString::class)) {
    \class_alias(\MonorepoBuilder20210801\Nette\HtmlStringable::class, \MonorepoBuilder20210801\Nette\Utils\IHtmlString::class);
}
namespace MonorepoBuilder20210801\Nette\Localization;

if (\false) {
    /** @deprecated use Nette\Localization\Translator */
    interface ITranslator extends \MonorepoBuilder20210801\Nette\Localization\Translator
    {
    }
} elseif (!\interface_exists(\MonorepoBuilder20210801\Nette\Localization\ITranslator::class)) {
    \class_alias(\MonorepoBuilder20210801\Nette\Localization\Translator::class, \MonorepoBuilder20210801\Nette\Localization\ITranslator::class);
}
