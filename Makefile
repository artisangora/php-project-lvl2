install:
	composer install
validate:
	composer validate

lint:
	./vendor/bin/phpcs --standard=PSR12 src bin
test:
	./vendor/bin/phpunit -c phpunit.xml.dist
test-coverage:
	./vendor/bin/phpunit -c phpunit.xml.dist --coverage-clover build/logs/clover.xml

php:
	docker-compose -f docker-compose-dev.yml up -d --build
	docker-compose -f docker-compose-dev.yml exec php-cli bash