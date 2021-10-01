deploy_dev:
	docker-compose -f ./docker/docker-compose.yaml up -d --build desafio-back_dev
	make install
	make cron
deploy_hml:
	docker-compose -f ./docker/docker-compose.yaml up -d --build desafio-back_hml
	make install
	make cron
cron:
	docker-compose -f ./docker/docker-compose.yaml up -d --build cron
install:
	docker exec desafio-back composer install
test:
	docker exec desafio-back php ./vendor/bin/pest --ci
down:
	docker-compose -f ./docker/docker-compose.yaml down