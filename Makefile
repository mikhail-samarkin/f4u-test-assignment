APP_DIR := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
INCLUDE_ALL_TARGETS:=true
DC = docker-compose
COMPOSER = $(DC) exec composer composer
APP = $(DC) exec -T app


-include $(APP_DIR)/.env


##build			Run build service
build: check-env build-container composer-local migrate load-fixtures

##run			Run container
run:
	$(DC) up -d

##build-container			Run build container
build-container:
	$(DC) up --build -d

##migrate			Run migration
migrate:
	$(APP) php bin/console doctrine:migrations:migrate --no-interaction

##composer-local		Install composer requirements
composer-local:
	$(COMPOSER) install --prefer-dist --no-interaction

##check-env		Check .env file
check-env:
	if [ ! -f .env ]; then cp -v .env.dist .env ; fi

##php-cs		Run PHP CS with option DRY
php-cs:
	$(APP) vendor/bin/phpcs src --standard=PSR12

##php-cs-fix		Run PHP B&F
php-cs-fix:
	$(APP)  vendor/bin/phpcbf src --standard=PSR12

phpstan: ## Run static code analyzer
	$(APP) vendor/bin/phpstan analyse -l 8 -c phpstan.neon src

load-fixtures: ## Load fixtures with clients
	$(APP) php bin/console doctrine:fixtures:load

input: ## Input to container
	docker-compose exec app bash