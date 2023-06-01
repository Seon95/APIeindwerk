# Stage 1: Base image
FROM composer AS build

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

# Stage 2: Final image
FROM php:8.1-fpm

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=build /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .

# Copy vendor dependencies from the build stage
COPY --from=build /var/www/html/vendor/ ./vendor/

# Set permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Generate optimized autoload files
RUN composer dump-autoload --optimize


# Expose port
EXPOSE 80

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
