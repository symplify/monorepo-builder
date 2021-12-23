<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace MonorepoBuilder20211223\Nette\Utils;

use MonorepoBuilder20211223\Nette;
if (\false) {
    /** @deprecated use Nette\HtmlStringable */
    interface IHtmlString extends \MonorepoBuilder20211223\Nette\HtmlStringable
    {
    }
} elseif (!\interface_exists(\MonorepoBuilder20211223\Nette\Utils\IHtmlString::class)) {
    \class_alias(\MonorepoBuilder20211223\Nette\HtmlStringable::class, \MonorepoBuilder20211223\Nette\Utils\IHtmlString::class);
}
namespace MonorepoBuilder20211223\Nette\Localization;

if (\false) {
    /** @deprecated use Nette\Localization\Translator */
    interface ITranslator extends \MonorepoBuilder20211223\Nette\Localization\Translator
    {
    }
} elseif (!\interface_exists(\MonorepoBuilder20211223\Nette\Localization\ITranslator::class)) {
    \class_alias(\MonorepoBuilder20211223\Nette\Localization\Translator::class, \MonorepoBuilder20211223\Nette\Localization\ITranslator::class);
}
