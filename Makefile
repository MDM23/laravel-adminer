LARAVEL_REPO ?= "https://github.com/laravel/laravel.git"
LARAVEL_VERSION ?= "master"

B := $(CURDIR)/tmp

.PHONY: test
test: ${B}/laravel
	cd ${B}/laravel && \
		git reset --hard HEAD && \
		git clean -ffxd && \
		git checkout ${LARAVEL_VERSION} && \
		git pull && \
		composer install
	$(MAKE) ${B}/laravel/.env
	$(MAKE) ${B}/laravel/composer.json
	cd ${B}/laravel && composer require mdm23/laravel-adminer:*@dev
	cd ${B}/laravel && vendor/bin/phpunit ../../tests/AdminerTest.php

.PHONY: ${B}/laravel/composer.json
${B}/laravel/composer.json:
	cd ${B}/laravel && \
		composer config repo.mdm23/laravel-adminer '{"type": "path", "url": "../../src", "options": {"symlink": true}}' && \
		composer config minimum-stability dev

${B}/laravel:
	git clone ${LARAVEL_REPO} ${B}/laravel

${B}/laravel/.env:
	echo "APP_KEY=" > ${B}/laravel/.env
	php ${B}/laravel/artisan key:generate

.PHONY: resources/adminer.php
resources/adminer.php:
	curl -L https://www.adminer.org/latest.php > resources/adminer.php
	LC_ALL=C sed -i -r 's/([)\;]|^)redirect/\1adminer_redirect/g' resources/adminer.php
	LC_ALL=C sed -i -r 's/([)\;]|^)cookie/\1adminer_cookie/g' resources/adminer.php
	LC_ALL=C sed -i -r 's/([)\;]|^)view/\1adminer_view/g' resources/adminer.php
