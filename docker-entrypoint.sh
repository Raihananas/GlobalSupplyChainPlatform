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

# Remove local fallback DB settings from .env so Railway system environment variables take precedence
sed -i '/^DB_HOST=/d' .env 2>/dev/null || true
sed -i '/^DB_PORT=/d' .env 2>/dev/null || true
sed -i '/^DB_DATABASE=/d' .env 2>/dev/null || true
sed -i '/^DB_USERNAME=/d' .env 2>/dev/null || true
sed -i '/^DB_PASSWORD=/d' .env 2>/dev/null || true
sed -i '/^DATABASE_URL=/d' .env 2>/dev/null || true
sed -i '/^MYSQL_URL=/d' .env 2>/dev/null || true

# Dynamically append Railway DB environment variables to .env if set
[ -n "$DB_HOST" ] && echo "DB_HOST=$DB_HOST" >> .env || ([ -n "$MYSQLHOST" ] && echo "DB_HOST=$MYSQLHOST" >> .env)
[ -n "$DB_PORT" ] && echo "DB_PORT=$DB_PORT" >> .env || ([ -n "$MYSQLPORT" ] && echo "DB_PORT=$MYSQLPORT" >> .env)
[ -n "$DB_DATABASE" ] && echo "DB_DATABASE=$DB_DATABASE" >> .env || ([ -n "$MYSQLDATABASE" ] && echo "DB_DATABASE=$MYSQLDATABASE" >> .env)
[ -n "$DB_USERNAME" ] && echo "DB_USERNAME=$DB_USERNAME" >> .env || ([ -n "$MYSQLUSER" ] && echo "DB_USERNAME=$MYSQLUSER" >> .env)
[ -n "$DB_PASSWORD" ] && echo "DB_PASSWORD=$DB_PASSWORD" >> .env || ([ -n "$MYSQLPASSWORD" ] && echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env)

# Ensure APP_KEY exists in .env
if ! grep -q "^APP_KEY=base64" .env; then
    if [ -n "$APP_KEY" ]; then
        echo "APP_KEY=$APP_KEY" >> .env
    else
        echo "Setting fallback APP_KEY..."
        echo "APP_KEY=base64:BartVDYYqLF/fMh8JRYr8n+D8jvKqCTCaAgxBjE1+so=" >> .env
    fi
fi

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

