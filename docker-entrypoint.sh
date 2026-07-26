#!/bin/sh
set -e

echo "=== Starting Laravel Application setup for Railway / Docker ==="

# Create required directories and set permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache storage/logs
chmod -R 777 storage bootstrap/cache

# Ensure .env file exists in container
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

# Ensure APP_KEY exists in .env
if ! grep -q "^APP_KEY=base64" .env; then
    echo "Setting APP_KEY..."
    sed -i 's/^APP_KEY=.*/APP_KEY=base64:BartVDYYqLF\/fMh8JRYr8n+D8jvKqCTCaAgxBjE1+so=/g' .env || echo "APP_KEY=base64:BartVDYYqLF/fMh8JRYr8n+D8jvKqCTCaAgxBjE1+so=" >> .env
fi

# Set APP_DEBUG=true temporarily for diagnosis
export APP_DEBUG=true
export APP_KEY="base64:BartVDYYqLF/fMh8JRYr8n+D8jvKqCTCaAgxBjE1+so="

# Clear old cache to ensure dynamic env loading
echo "Clearing application cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations & seeders synchronously
if [ -n "$DB_HOST" ] || [ -n "$MYSQLHOST" ] || [ -n "$DATABASE_URL" ] || [ -n "$MYSQL_URL" ]; then
    echo "Running database migrations..."
    php artisan migrate --force --no-interaction || echo "Warning: Migration failed."
    echo "Running database seeders..."
    php artisan db:seed --force --no-interaction || echo "Warning: Seeding failed."
fi

PORT=${PORT:-8080}
echo "=== Starting Laravel HTTP Server on 0.0.0.0:${PORT} ==="
exec php artisan serve --host=0.0.0.0 --port=$PORT
