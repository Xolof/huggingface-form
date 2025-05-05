#!/usr/bin/bash
vendor/bin/php-cs-fixer fix src tests
vendor/bin/phpcs src tests -s
vendor/bin/phpcbf src tests
vendor/bin/phpmd src text cleancode
vendor/bin/phpmd tests text cleancode
vendor/bin/phpstan analyse src tests --memory-limit="512M" --level=5
rm test.db
sqlite3 test.db < setup.sql
vendor/bin/phpunit tests --coverage-html coverage/ --colors
