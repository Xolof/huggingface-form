#!/usr/bin/bash
pretty-php --space=4 --no-simplify-strings src 
php-cs-fixer fix src
#phpcs src
#phpcbf src
phpmd src text cleancode
phpcpd src 
phpstan analyse src --memory-limit="512M" --level=6

