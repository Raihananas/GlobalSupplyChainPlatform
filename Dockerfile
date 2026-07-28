FROM php:8.2-cli

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first to leverage Docker layer caching
COPY composer.json composer.lock ./

# Install dependencies without running scripts yet
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy remaining source code
COPY . .

# Run composer post-autoload dump
RUN composer dump-autoload --optimize

# Make entrypoint script executable
RUN chmod +x /var/www/docker-entrypoint.sh

# Set permissions for storage & bootstrap cache
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
