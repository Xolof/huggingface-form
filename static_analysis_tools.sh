#!/usr/bin/bash
vendor/bin/php-cs-fixer fix src
vendor/bin/phpcs src -s
vendor/bin/phpcbf src
vendor/bin/phpmd src text cleancode
vendor/bin/phpstan analyse src --memory-limit="512M" --level=5
