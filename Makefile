# Executables (local)
DOCKER_COMP = docker compose --env-file frankenphp/env/docker-compose.override.env

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# PHP container executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        = help build up run down logs php

##—————— calc.sio ——————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s \033[0m %s\n", $$1 e, $$2}' |  sed -e 's/\[32m##/[33m/'

env-create: ## Create environment variables
	cp -i frankenphp/env/docker-compose.env frankenphp/env/docker-compose.override.env

install: ## Create environment variables and build the Docker images
	make env-create
	make rebuild
	make up
	make ps

##—————— 🐳 Docker ——————
build: ## Builds the Docker images (with cache)
	$(DOCKER_COMP) build --pull php

rebuild: ## Rebuilds the Docker images
	$(DOCKER_COMP) build --pull --no-cache

chown: ## Change the owner of file system files and directories
	$(DOCKER_COMP) run --rm php chown -R 1000:1000 .

ps: ## List containers
	$(DOCKER_COMP) ps

run: ## Start the docker hub in attached mode (with logs)
	$(DOCKER_COMP) up

up: ## Start the docker hub in detached mode (no logs)
	$(DOCKER_COMP) up --detach

log: ## Show and follow tail of live logs
	$(DOCKER_COMP) logs --tail=20 --follow

down: ## Stop the docker hub
	$(DOCKER_COMP) down --remove-orphans

##—————— 🐳 Docker container ——————
php: ## Connect to the PHP FPM container
	$(PHP_CONT) sh

php-check-all: ## Check code style, static code analysis, audit the composer packages and run tests
	$(PHP_CONT) check-all

