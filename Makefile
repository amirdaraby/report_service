SHELL := /bin/bash

ifneq (,$(wildcard .env))
include .env
export
endif

.PHONY: setup up init-elastic elastic-api-key restart-workers key-generate migrate down logs

setup:
	@test -f .env || (echo "Missing .env. Run: cp .env.example .env" && exit 1)
	$(MAKE) up
	$(MAKE) init-elastic
	$(MAKE) elastic-api-key
	$(MAKE) restart-workers
	$(MAKE) key-generate
	$(MAKE) migrate
	@echo ""
	@echo "Setup completed."

up:
	@docker compose up -d --build 2>&1 | cat

init-elastic:
	@echo "Initializing Elasticsearch..."
	@echo "Setting kibana_system password..."
	@docker compose exec -T elasticsearch \
		curl -fsS \
		-u "elastic:$(ELASTIC_PASSWORD)" \
		-X PUT \
		http://localhost:9200/_security/user/kibana_system/_password \
		-H "Content-Type: application/json" \
		-d '{"password":"$(KIBANA_PASSWORD)"}'
	@echo "Creating report_service_role..."
	@docker compose exec -T elasticsearch \
		curl -fsS -u elastic:$${ELASTIC_PASSWORD} \
		-X PUT "http://localhost:9200/_security/role/report_service_role" \
		-H "Content-Type: application/json" \
		-d '{"cluster":["manage_own_api_key"],"indices":[{"names":["posts*"],"privileges":["read","write","create_index","view_index_metadata"]}]}'
	@echo "Creating report_service user..."
	@docker compose exec -T elasticsearch \
		curl -fsS -u elastic:$${ELASTIC_PASSWORD} \
		-X PUT "http://localhost:9200/_security/user/$${ELASTICSEARCH_USERNAME}" \
		-H "Content-Type: application/json" \
		-d '{"password":"'"$${ELASTICSEARCH_PASSWORD}"'","roles":["report_service_role"],"full_name":"report service app"}'
	@docker compose restart kibana 2>&1 | cat
	@echo "Elasticsearch initialization completed."

elastic-api-key:
	@docker compose exec -T php_fpm php artisan elasticsearch:get-api-key

restart-workers:
	@docker compose restart queue_worker scheduler 2>&1 | cat
	@echo "Restarted queue_worker and scheduler."

key-generate:
	@if [ -n "$(APP_KEY)" ]; then \
		echo "APP_KEY already exists, skipping generation."; \
	else \
		docker compose exec php_fpm php artisan key:generate; \
	fi

migrate:
	docker compose exec php_fpm php artisan migrate

down:
	docker compose down

logs:
	docker compose logs --tail=100 -f
