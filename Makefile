LARAVEL_REPO ?= "https://github.com/laravel/laravel.git"
LARAVEL_VERSION ?= "master"

B := $(CURDIR)/tmp

# The default make target. This installs all dependencies of this package into
# the vendor directory. Currently, these are only the development dependencies
# which are required for the automated tests.
vendor:
	composer install

# Runs all end-to-end tests. The target first installs all dependencies and a
# temporary installation of Laravel (its version can be defined with the
# environment variable LARAVEL_VERSION). Afterwards, a minimal configuration is
# deployed and the package installs itself. When everything was successfull,
# the test routines are started.
.PHONY: test
test: vendor ${B}/laravel
	cd ${B}/laravel && \
		git reset --hard HEAD && \
		git clean -ffxd && \
		git checkout ${LARAVEL_VERSION} && \
		git pull && \
		composer install
	$(MAKE) ${B}/laravel/.env
	$(MAKE) ${B}/laravel/composer.json
	cd ${B}/laravel && composer require mdm23/laravel-adminer:*@dev
	php tests/e2e.php

# Prepare the composer.json file of our temporary Laravel installation. We need
# to configure a local repository that points to our package sources.
.PHONY: ${B}/laravel/composer.json
${B}/laravel/composer.json:
	cd ${B}/laravel && \
		composer config repo.mdm23/laravel-adminer '{"type": "path", "url": "../../src", "options": {"symlink": true}}' && \
		composer config minimum-stability dev

# Deploys the configured Laravel installation to the temporary build folder.
${B}/laravel:
	git clone ${LARAVEL_REPO} ${B}/laravel

# Prepare a minimal environment configuration for Laravel. We need at least set
# a valid encryption key.
${B}/laravel/.env:
	echo "APP_KEY=" > ${B}/laravel/.env
	php ${B}/laravel/artisan key:generate

# This target downloads the latest adminer file. Afterwards, it refactors the
# name of some internal functions which would cause a naming-conflict with
# Laravel.
.PHONY: resources/adminer.php
resources/adminer.php:
	curl -L https://www.adminer.org/latest.php > resources/adminer.php
	LC_ALL=C sed -i -r 's/([)\;]|^)redirect/\1adminer_redirect/g' resources/adminer.php
	LC_ALL=C sed -i -r 's/([)\;]|^)cookie/\1adminer_cookie/g' resources/adminer.php
	LC_ALL=C sed -i -r 's/([)\;]|^)view/\1adminer_view/g' resources/adminer.php
