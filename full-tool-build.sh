#!/usr/bin/env bash

# add patches
composer install --ansi

# but skip dev dependencies
composer update --no-dev --ansi

# remove tests and useless files, to make downgraded, scoped and deployed codebase as small as possible
rm -rf tests

# downgrade with rector
mkdir rector-local
composer require rector/rector --working-dir rector-local
rector-local/vendor/bin/rector process bin src packages vendor --config build/rector-downgrade-php-72.php --ansi

# prefix
sh prefix-code.sh
