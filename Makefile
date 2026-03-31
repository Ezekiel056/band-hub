docker-up:
	docker compose --env-file app/.env.local up -d

docker-down:
	docker compose --env-file app/.env.local down

bash:
	docker compose exec php bash

logs:
	docker compose logs -f

migration:
	docker compose exec php bash -c "php bin/console make:migration"

migrate:
	docker compose exec php bash -c "php bin/console doctrine:migrations:migrate --no-interaction"