up: docker-up
down: docker-stop
down: docker-down
state: docker-ps
restart: docker-down docker-up
logs: docker-logs

watch:
	docker-compose exec frontend-nodejs npm run watch

############################
## Docker
############################
docker-up:
	docker-compose up -d --build
docker-down:
	docker-compose down -v --remove-orphans
docker-stop:
	docker-compose stop
docker-ps:
	docker-compose ps
docker-build:
	docker-compose build
docker-pull:
	docker-compose pull
docker-logs:
	docker-compose logs -f

watch:
	docker-compose exec frontend-nodejs npm run watch

api-init: api-composer-install api-oauth-keys

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-composer-update:
	docker-compose run --rm api-php-cli composer update

api-composer-require:
	docker-compose run --rm api-php-cli composer require

api-composer-outdated:
	docker-compose run --rm api-php-cli composer outdated

api-migrations:
	docker-compose run --rm api-php-cli composer app migrations:migrate --no-interaction

api-migration-diff:
	docker-compose run --rm api-php-cli composer app migrations:diff --no-interaction

api-fixtures:
	docker-compose run --rm api-php-cli composer app fixtures:load --no-interaction

api-test:
	docker-compose run --rm api-php-cli composer test

api-docs:
	docker-compose run --rm api-php-cli echo "TODO api doc generate"

api-oauth-keys:
	docker-compose run --rm api-php-cli mkdir -p var/oauth
	docker-compose run --rm api-php-cli openssl genrsa -out var/oauth/private.key 2048
	docker-compose run --rm api-php-cli openssl rsa -in var/oauth/private.key -pubout -out var/oauth/public.key
	docker-compose run --rm api-php-cli chmod 644 var/oauth/private.key var/oauth/public.key
