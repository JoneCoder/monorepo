#!/bin/bash

cd /var/www/html
yarn
yarn generate:proto
yarn build

service supervisor start
supervisorctl reread
supervisorctl update
supervisorctl start all

exec nginx -g "daemon off;"

