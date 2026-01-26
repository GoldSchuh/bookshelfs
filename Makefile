#
# 	- SPDX-FileCopyrightText: 2019-2024 Nextcloud GmbH and Nextcloud contributors
# 	- SPDX-FileCopyrightText: 2026 Kars van Velzen
# 	- SPDX-License-Identifier: AGPL-3.0-or-later
#
app_name=bookshelfs
app_version=$(version)
project_dir=.
build_dir=/tmp/build
sign_dir=/tmp/sign
cert_dir=$(HOME)/.nextcloud/certificates
webserveruser ?= www-data
occ_dir ?= /var/www/html/dev/server
build_tools_directory=$(CURDIR)/build/tools
npm=$(shell which npm 2> /dev/null)
composer=$(shell which composer 2> /dev/null)

all: build

.PHONY: build
build:
ifneq (,$(wildcard $(CURDIR)/composer.json))
	make composer
endif
ifneq (,$(wildcard $(CURDIR)/package.json))
	make npm
endif

.PHONY: dev
dev:
ifneq (,$(wildcard $(CURDIR)/composer.json))
	make composer
endif
ifneq (,$(wildcard $(CURDIR)/package.json))
	make npm-dev
endif

# Installs and updates the composer dependencies. If composer is not installed a copy is fetched from the web
.PHONY: composer
composer:
ifeq (, $(composer))
	@echo "No composer command available, downloading a copy from the web"
	mkdir -p $(build_tools_directory)
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar $(build_tools_directory)
	php $(build_tools_directory)/composer.phar install --prefer-dist
else
	composer install --prefer-dist
endif
.PHONY: composer_release
composer_release:
ifeq (, $(composer))
	@echo "No composer command available, downloading a copy from the web"
	mkdir -p $(build_tools_directory)
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar $(build_tools_directory)
	php $(build_tools_directory)/composer.phar install --prefer-dist
else
	composer install --no-dev -a
endif

.PHONY: npm
npm:
	$(npm) ci
	$(npm) run build

.PHONY: npm-dev
npm-dev:
	$(npm) ci
	$(npm) run watch

clean:
	sudo rm -rf $(build_dir)
	sudo rm -rf $(sign_dir)
	rm -rf js/* vendor

# Builds and sign a release tarball
build_release: clean composer_release npm
	mkdir -p $(sign_dir)
	mkdir -p $(build_dir)
	@rsync -a \
	--exclude=.git \
	--exclude=.github \
	--exclude=.idea \
	--exclude=docs \
	--exclude=node_modules \
	--exclude=src \
	--exclude=tests \
	--exclude=vendor-bin \
	--exclude=translationfiles \
	--exclude=.gitignore \
	--exclude=.nvmrc \
	--exclude=/.php* \
	--exclude=CODE_OF_CONDUCT.md \
	--exclude=composer.json \
	--exclude=composer.lock \
	--exclude=composer.phar \
	--exclude=eslint.config.js \
	--exclude=Makefile \
	--exclude=package.json \
	--exclude=package-lock.json \
	--exclude=/psalm.xml \
	--exclude=stylelint.config.cjs \
	--exclude=tsconfig.json \
	--exclude=vite.config.ts \
	$(project_dir) $(sign_dir)/$(app_name)
	@if [ -f $(cert_dir)/$(app_name).key ]; then \
		sudo chown $(webserveruser) $(sign_dir)/$(app_name)/appinfo ;\
		sudo -u $(webserveruser) php $(occ_dir)/occ integrity:sign-app --privateKey=$(cert_dir)/$(app_name).key --certificate=$(cert_dir)/$(app_name).crt --path=$(sign_dir)/$(app_name)/ ;\
		sudo chown -R $(USER) $(sign_dir)/$(app_name)/appinfo ;\
	else \
		echo "!!! WARNING signature key not found" ;\
	fi
	tar -czf $(build_dir)/$(app_name)-$(app_version).tar.gz \
		-C $(sign_dir) $(app_name)
	@if [ -f $(cert_dir)/$(app_name).key ]; then \
		echo NEXTCLOUD------------------------------------------ ;\
		openssl dgst -sha512 -sign $(cert_dir)/$(app_name).key $(build_dir)/$(app_name)-$(app_version).tar.gz | openssl base64 | tee $(build_dir)/sign.txt ;\
	fi
