#!/bin/sh
set -e

echo "=== Starting Laravel Application setup for Railway / Docker ==="

# Create required directories and set permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache storage/logs
chmod -R 777 storage bootstrap/cache

# Fallback APP_KEY if not set in environment
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:BartVDYYqLF/fMh8JRYr8n+D8jvKqCTCaAgxBjE1+so="
fi

# Clear old cache to avoid stale environment issues
echo "Clearing cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations & seeders synchronously so tables exist when users access site
if [ -n "$DB_HOST" ] || [ -n "$MYSQLHOST" ] || [ -n "$DATABASE_URL" ] || [ -n "$MYSQL_URL" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction || echo "Warning: Migration failed."
    echo "Running database seeders..."
    php artisan db:seed --force --no-interaction || echo "Warning: Seeding failed."
fi

# Re-cache for production
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

PORT=${PORT:-8080}
echo "=== Starting Laravel HTTP Server on 0.0.0.0:${PORT} ==="
exec php artisan serve --host=0.0.0.0 --port=$PORT
