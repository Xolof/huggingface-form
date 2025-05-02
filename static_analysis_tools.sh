#!/usr/bin/bash
php-cs-fixer fix src
phpcs src -s
phpcbf src
phpmd src text cleancode
phpcpd src 
phpstan analyse src --memory-limit="512M" --level=5
