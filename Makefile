docker-config:
	docker compose --env-file app/.env.local config

docker-up:
	docker compose --env-file app/.env.local up -d

docker-up-build:
	docker compose --env-file app/.env.local up -d --build

docker-down:
	docker compose --env-file app/.env.local down

bash:
	docker compose exec php bash

logs:
	docker compose logs -f

migration:
	docker compose --env-file app/.env.local exec php bash -c "php bin/console make:migration"

entity:
	docker compose --env-file app/.env.local exec php bash -c "php bin/console make:entity"

test:
	docker compose --env-file app/.env.local exec php bash -c "php bin/console make:test"

migrate:
	docker compose --env-file app/.env.local exec php bash -c "php bin/console doctrine:migrations:migrate --no-interaction"

tailwind:
	docker compose --env-file app/.env.local exec php bash -c "php bin/console tailwind:build --watch"

fixtures:
	docker compose --env-file app/.env.local exec php php bin/console doctrine:fixtures:load --no-interaction

install-mongodb:
	docker compose --env-file app/.env.local exec php composer require doctrine/mongodb-odm-bundle

run-tests:
	docker compose --env-file app/.env.local exec php bin/phpunit

run-tests-unit:
	docker compose --env-file app/.env.local exec php bin/phpunit tests/TestCase

run-tests-fonctionnal:
	docker compose --env-file app/.env.local exec php bin/phpunit tests/WebTestCase