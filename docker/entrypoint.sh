#!/bin/bash
set -e

if [ -n "${DATABASE_URL:-}" ] && [ -z "${DB_URL:-}" ]; then
    export DB_URL="$DATABASE_URL"
fi

if [ -n "${PORT:-}" ] && [ "$PORT" != "80" ]; then
    sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

if [ -z "${APP_KEY:-}" ]; then
    export APP_KEY="base64:$(openssl rand -base64 32)"
fi

if [ -n "${DB_HOST:-}" ] && [ -n "${DB_DATABASE:-}" ]; then
    php artisan migrate --force --no-interaction || echo "WARN: migrations failed"
    php artisan db:seed --force --class=AdminSeeder --no-interaction || echo "WARN: admin seed failed"
    php artisan wpu:optimize-production --force --no-interaction || echo "WARN: optimize failed"
else
    echo "WARN: DB_HOST/DB_DATABASE not set — skipping migrations until MySQL is configured."
fi

exec "$@"
