SERVICE_NAME = app
DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_RUN = docker compose run --rm $(SERVICE_NAME)

# Define the commands to run inside the Docker Compose service
COMPOSER_INSTALL = $(DOCKER_COMPOSE_RUN) composer install
NPM_INSTALL = $(DOCKER_COMPOSE_RUN) npm install
NPM_BUILD = $(DOCKER_COMPOSE_RUN) npm run build
COMPOSER_UPDATE = $(DOCKER_COMPOSE_RUN) composer update

ARTISAN_DB = $(DOCKER_COMPOSE_RUN) php artisan db
ARTISAN_KEY_GENERATE = $(DOCKER_COMPOSE_RUN) php artisan key:generate
ARTISAN_MIGRATE = $(DOCKER_COMPOSE_RUN) php artisan migrate
ARTISAN_MIGRATE_FRESH_SEED = $(DOCKER_COMPOSE_RUN) php artisan migrate:fresh --seed
ARTISAN_DB_SEED = $(DOCKER_COMPOSE_RUN) php artisan db:seed
ARTISAN_SERVE = $(DOCKER_COMPOSE_RUN) php artisan serve --host=0.0.0.0

# Define the default target
all: install

init:
	@test -f .env || cp .env.example .env
	$(DOCKER_COMPOSE) down --remove-orphans
	@make install
	$(ARTISAN_KEY_GENERATE)
	$(NPM_BUILD)
	$(ARTISAN_MIGRATE)
	$(ARTISAN_MIGRATE_FRESH_SEED)

up:
	$(DOCKER_COMPOSE) down --remove-orphans
	$(DOCKER_COMPOSE) build
	$(DOCKER_COMPOSE) up -d

frontend:
	$(NPM_BUILD)

ssh:
	$(DOCKER_COMPOSE) run $(SERVICE_NAME) /bin/bash

# Define targets for each command
install:
	$(DOCKER_COMPOSE) build
	$(COMPOSER_INSTALL)
	$(NPM_INSTALL)

build:
	$(DOCKER_COMPOSE) build

npm:
	$(NPM_INSTALL)

update:
	$(COMPOSER_UPDATE)

migrate:
	$(ARTISAN_MIGRATE)

migrate-fresh-seed:
	$(ARTISAN_MIGRATE_FRESH_SEED)

db-seed:
	$(ARTISAN_DB_SEED)
