# Makefile

# Commandes Docker
DOCKER_COMPOSE = docker-compose -f infra/docker-compose.yml

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: docker-build docker-up composer-install## install

start: setup-transports migrations-migrate ## start

reroll: docker-down docker-up composer-dump cache-clear start

setup-transports:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console messenger:setup-transports
	@$(DOCKER_COMPOSE) exec request-api php bin/console messenger:setup-transports

composer-pull: ## composer-pull
	@echo "Téléchargement de l'image Composer..."
	@docker pull composer:latest

composer-install: ## Installe les dépendances Composer
	@echo "Installation des dépendances Composer..."
	@$(DOCKER_COMPOSE) exec discussion-api composer install
	@$(DOCKER_COMPOSE) exec request-api composer install

composer-dump: ## dump autoload
	@echo "Installation des dépendances Composer..."
	@$(DOCKER_COMPOSE) exec discussion-api composer dump-autoload
	@$(DOCKER_COMPOSE) exec request-api composer dump-autoload

cache-clear: ## cache clear
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console c:c
	@$(DOCKER_COMPOSE) exec request-api php bin/console c:c

composer-update: ## Met à jour les dépendances Composer
	@echo "Update des dépendances Composer..."
	@$(DOCKER_COMPOSE) exec discussion-api composer update
	@$(DOCKER_COMPOSE) exec request-api composer update

docker-build: ## Construit les conteneurs Docker
	@echo "Construction des conteneurs Docker..."
	@$(DOCKER_COMPOSE) build

docker-up: ## Démarre les conteneurs Docker
	@echo "Démarrage des conteneurs Docker..."
	@$(DOCKER_COMPOSE) up -d

docker-down: ## Arrête les conteneurs Docker
	@echo "Arrêt des conteneurs Docker..."
	@$(DOCKER_COMPOSE) down

migrations-migrate: ## Exécute les migrations Doctrine
	@echo "Exécution des migrations Doctrine..."
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console doctrine:migrations:migrate --no-interaction
	@$(DOCKER_COMPOSE) exec request-api php bin/console doctrine:migrations:migrate --no-interaction

logs: ## Affiche les logs des conteneurs
	@$(DOCKER_COMPOSE) logs -f

discussion-api-shell: ## Ouvre un shell sur le container discussion-api
	@$(DOCKER_COMPOSE) run discussion-api bash
discussion-api-consume-event_outbox:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console --profile messenger:consume event_outbox -l 1 -vvv
discussion-api-consume-event_outbox_failed:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console --profile messenger:consume event_outbox_failed -l 1 -vvv
discussion-api-consume-event:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console --profile messenger:consume event --bus event.bus -vvv
discussion-api-consume-failed:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console --profile messenger:consume failed -l 1 --bus event.bus -vvv

request-api-shell: ## Ouvre un shell sur le container request-api
	@$(DOCKER_COMPOSE) run request-api bash
request-api-consume-event_outbox:
	@$(DOCKER_COMPOSE) exec request-api php bin/console --profile messenger:consume event_outbox -vvv
request-api-consume-event_outbox_failed:
	@$(DOCKER_COMPOSE) exec request-api php bin/console --profile messenger:consume event_outbox_failed -l 1 -vvv
request-api-consume-event:
	@$(DOCKER_COMPOSE) exec request-api php bin/console --profile messenger:consume event --bus event.bus -vvv
request-api-consume-failed:
	@$(DOCKER_COMPOSE) exec request-api php bin/console --profile messenger:consume failed -l 1 --bus event.bus -vvv

disc-test:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console --profile messenger:consume event -vvv