# Makefile

# Define the Docker Compose service name
SERVICE_NAME = app

# Define the Docker Compose command
DOCKER_COMPOSE = docker-compose

# Define the commands to run inside the Docker Compose service
COMPOSER_INSTALL = $(DOCKER_COMPOSE) exec $(SERVICE_NAME) composer install
COMPOSER_UPDATE = $(DOCKER_COMPOSE) exec $(SERVICE_NAME) composer update
ARTISAN_DB = $(DOCKER_COMPOSE) exec $(SERVICE_NAME) php artisan db
ARTISAN_KEY_GENERATE = $(DOCKER_COMPOSE) exec $(SERVICE_NAME) php artisan key:generate
ARTISAN_MIGRATE = $(DOCKER_COMPOSE) exec $(SERVICE_NAME) php artisan migrate
ARTISAN_MIGRATE_FRESH_SEED = $(DOCKER_COMPOSE) exec $(SERVICE_NAME) php artisan migrate:fresh --seed
ARTISAN_DB_SEED = $(DOCKER_COMPOSE) exec $(SERVICE_NAME) php artisan db:seed

# Define the default target
all: install

# Define targets for each command
install:
	$(COMPOSER_INSTALL)

update:
	$(COMPOSER_UPDATE)

key-generate:
	$(ARTISAN_KEY_GENERATE)

migrate:
	$(ARTISAN_MIGRATE)

migrate-fresh-seed:
	$(ARTISAN_MIGRATE_FRESH_SEED)

db-seed:
	$(ARTISAN_DB_SEED)
