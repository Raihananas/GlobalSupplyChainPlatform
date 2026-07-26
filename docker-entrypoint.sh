#!/bin/sh
set -e

echo "=== Starting Laravel Application setup for Railway / Docker ==="

# Create required directories if missing
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chmod -R 777 storage bootstrap/cache

# If command passed is php-fpm (e.g. from local docker-compose), execute php-fpm
if [ "$1" = "php-fpm" ]; then
    echo "Running in PHP-FPM mode..."
    exec php-fpm
fi

# Clear old cache to avoid stale environment issues
echo "Clearing cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations and seed database if DB is configured
if [ -n "$DB_HOST" ] || [ -n "$MYSQLHOST" ] || [ -n "$DATABASE_URL" ] || [ -n "$MYSQL_URL" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction || echo "Warning: Migration failed or database not ready."
    echo "Running database seeders..."
    php artisan db:seed --force --no-interaction || echo "Warning: Database seeding skipped/failed."
fi

# Re-cache for production optimization
echo "Optimizing application cache..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

PORT=${PORT:-8080}
echo "=== Starting Laravel HTTP Server on 0.0.0.0:${PORT} ==="
exec php artisan serve --host=0.0.0.0 --port=$PORT
