#!/bin/sh
set -e

echo "=== Starting Laravel Application setup for Railway / Docker ==="

# Create required directories and set permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache storage/logs database
chmod -R 777 storage bootstrap/cache database

# Ensure .env file exists in container
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

# Remove local fallback DB settings from .env so system environment variables take precedence
sed -i '/^DB_HOST=/d' .env 2>/dev/null || true
sed -i '/^DB_PORT=/d' .env 2>/dev/null || true
sed -i '/^DB_DATABASE=/d' .env 2>/dev/null || true
sed -i '/^DB_USERNAME=/d' .env 2>/dev/null || true
sed -i '/^DB_PASSWORD=/d' .env 2>/dev/null || true
sed -i '/^DATABASE_URL=/d' .env 2>/dev/null || true
sed -i '/^MYSQL_URL=/d' .env 2>/dev/null || true

# Determine DB Host & Connection
DETECTED_HOST=""
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "127.0.0.1" ] && [ "$DB_HOST" != "localhost" ]; then
    DETECTED_HOST="$DB_HOST"
elif [ -n "$MYSQLHOST" ]; then
    DETECTED_HOST="$MYSQLHOST"
fi

if [ -n "$DETECTED_HOST" ] || [ -n "$DATABASE_URL" ] || [ -n "$MYSQL_URL" ]; then
    echo "MySQL connection detected ($DETECTED_HOST). Using MySQL database..."
    sed -i '/^DB_CONNECTION=/d' .env 2>/dev/null || true
    echo "DB_CONNECTION=mysql" >> .env
    [ -n "$DETECTED_HOST" ] && echo "DB_HOST=$DETECTED_HOST" >> .env
    [ -n "$DB_PORT" ] && echo "DB_PORT=$DB_PORT" >> .env || ([ -n "$MYSQLPORT" ] && echo "DB_PORT=$MYSQLPORT" >> .env)
    [ -n "$DB_DATABASE" ] && echo "DB_DATABASE=$DB_DATABASE" >> .env || ([ -n "$MYSQLDATABASE" ] && echo "DB_DATABASE=$MYSQLDATABASE" >> .env)
    [ -n "$DB_USERNAME" ] && echo "DB_USERNAME=$DB_USERNAME" >> .env || ([ -n "$MYSQLUSER" ] && echo "DB_USERNAME=$MYSQLUSER" >> .env)
    [ -n "$DB_PASSWORD" ] && echo "DB_PASSWORD=$DB_PASSWORD" >> .env || ([ -n "$MYSQLPASSWORD" ] && echo "DB_PASSWORD=$MYSQLPASSWORD" >> .env)
else
    echo "No external MySQL host detected. Falling back to SQLite database for instant compatibility..."
    SQLITE_PATH="/var/www/database/database.sqlite"
    touch "$SQLITE_PATH"
    chmod 777 "$SQLITE_PATH"
    sed -i '/^DB_CONNECTION=/d' .env 2>/dev/null || true
    sed -i '/^DB_DATABASE=/d' .env 2>/dev/null || true
    echo "DB_CONNECTION=sqlite" >> .env
    echo "DB_DATABASE=$SQLITE_PATH" >> .env
fi

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

echo "Running database migrations..."
php artisan migrate --force --no-interaction || echo "Warning: Migration failed."

echo "Running database seeders..."
php artisan db:seed --force --no-interaction || echo "Warning: Seeding failed."

PORT=${PORT:-8080}
echo "=== Starting Laravel HTTP Server on 0.0.0.0:${PORT} ==="
exec php artisan serve --host=0.0.0.0 --port=$PORT


