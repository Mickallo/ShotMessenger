# Makefile

# Commandes Docker
DOCKER_COMPOSE = docker-compose -f infra/docker-compose.yml

# Tâches
.PHONY: help install docker-build docker-up docker-down composer-install composer-update logs discussion-shell

help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: composer-install docker-build docker-up

start: migrations-migrate setup-transports

setup-transports:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console messenger:setup-transports

composer-pull:
	@echo "Téléchargement de l'image Composer..."
	@docker pull composer:latest

composer-install: ## Installe les dépendances Composer
	@echo "Installation des dépendances Composer..."
	@docker run --rm -v $(PWD)/discussion-api:/discussion-api -w /discussion-api composer install --ignore-platform-reqs

composer-update: ## Met à jour les dépendances Composer
	@echo "Téléchargement de l'image Composer..."
	@docker pull composer:latest
	@echo "Mise à jour des dépendances Composer..."
	@docker run --rm -v $(PWD)/discussion-api:/discussion-api -w /discussion-api composer update

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

logs: ## Affiche les logs des conteneurs
	@$(DOCKER_COMPOSE) logs -f

discussion-shell: ## Ouvre un shell sur le container discussion-api
	@$(DOCKER_COMPOSE) run discussion-api bash

discussion-consume-outbox:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console --profile messenger:consume outbox -l 1 -vv
discussion-consume-outbox_failed:
	@$(DOCKER_COMPOSE) exec discussion-api php bin/console --profile messenger:consume outbox_failed -l 1  -vv

gatling-test: ## Exécute les tests de charge Gatling
	@echo "Exécution des tests de charge Gatling..."
	@$(DOCKER_COMPOSE) run gatling
