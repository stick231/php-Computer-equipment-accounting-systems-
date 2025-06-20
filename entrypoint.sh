#!/usr/bin/env bash

export NGINX_CONF=${NGINX_CONF:-'/etc/nginx/nginx.conf'}
export PHP_FPM_CONF=${PHP_FPM_CONF:-'/etc/php/7.4/fpm/php.ini'}

TRAPPED_SIGNAL=false

if [[ -n "$DB_HOST" && -n "$DB_USER" && -n "$DB_PASSWORD" ]]; then
  echo "⏳ Ожидание запуска MySQL на $DB_HOST..."
  until mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; do
      echo "⏳ MySQL недоступен — ждём..."
      sleep 3
  done
fi

echo 'Starting NGINX'
nginx -c "$NGINX_CONF" -g 'daemon off;' 2>&1 &
NGINX_PID=$!

echo 'Starting PHP-FPM'
php-fpm7.4 -R -F -c "$PHP_FPM_CONF" 2>&1 &
PHP_FPM_PID=$!

trap "TRAPPED_SIGNAL=true; kill -15 $NGINX_PID; kill -15 $PHP_FPM_PID;" SIGTERM SIGINT

while :
do
    kill -0 $NGINX_PID 2> /dev/null
    NGINX_STATUS=$?

    kill -0 $PHP_FPM_PID 2> /dev/null
    PHP_FPM_STATUS=$?

    if [ "$TRAPPED_SIGNAL" = "false" ]; then
        if [ $NGINX_STATUS -ne 0 ] || [ $PHP_FPM_STATUS -ne 0 ]; then
            [ $NGINX_STATUS -eq 0 ] && kill -15 $NGINX_PID && wait $NGINX_PID
            [ $PHP_FPM_STATUS -eq 0 ] && kill -15 $PHP_FPM_PID && wait $PHP_FPM_PID
            exit 1
        fi
    else
        if [ $NGINX_STATUS -ne 0 ] && [ $PHP_FPM_STATUS -ne 0 ]; then
            exit 0
        fi
    fi

    sleep 1
done
