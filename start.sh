#!/bin/sh
APP_PATH="/usr/src/app"
echo "all params $@"
echo "applying creating db and run migrations"
${APP_PATH}/bin/console doctrine:database:create --if-not-exists
${APP_PATH}/bin/console doctrine:migrations:migrate --no-interaction
${APP_PATH}/bin/console doctrine:fixtures:load --no-interaction
echo "starting php-fpm"
exec $@
