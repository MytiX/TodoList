#!/usr/bin/env bash

COMPOSER_FLAGS="--no-progress --prefer-dist"
if [[ ${APP_ENV:="dev"} == "prod" ]]; then
    COMPOSER_FLAGS="--no-dev --optimize-autoloader --no-progress --prefer-dist"
fi

touch /tmp/DOING_COMPOSER_INSTALL

composer install ${COMPOSER_FLAGS}

chown -R www-data:www-data /var/www
chown -R www-data:www-data ${COMPOSER_HOME:="/var/lib/composer"}

rm /tmp/DOING_COMPOSER_INSTALL

/usr/local/bin/docker-php-entrypoint "$@"