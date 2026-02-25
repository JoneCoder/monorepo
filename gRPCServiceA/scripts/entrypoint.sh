#!/bin/bash
#set -Eero pipe fail
# shellcheck disable=SC2164

cd /var/www/html
composer install --no-interaction --optimize-autoloader
cp grpc/grpc_php_plugin /usr/local/bin/
chmod +x /usr/local/bin/grpc_php_plugin

chmod -R 777 storage/
chmod -R 775 .env
chmod -R 775 bootstrap/cache
chmod -R 775 public
chown -R www-data:www-data public/

php artisan storage:link
php artisan migrate --force
php artisan o:c

service php8.3-fpm restart
service supervisor start
supervisorctl reread
supervisorctl update
supervisorctl start all

exec nginx -g "daemon off;"
