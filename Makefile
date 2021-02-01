install:
	composer install
validate:
	composer validate

lint:
	composer run-script linter
test:
	./vendor/bin/phpunit tests

php:
	docker-compose -f docker-compose-dev.yml up -d --build
	docker-compose -f docker-compose-dev.yml exec php-cli bash

gendiff:
	./bin/gendiff
