# Stage 1: Build Composer dependencies
FROM composer:2 AS vendor
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Build Node assets
FROM node:18 AS nodebuild
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Stage 3: PHP Application
FROM php:8.2-fpm-alpine

# Install required extensions
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    git \
    curl \
 && docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip

WORKDIR /var/www/html

# Copy Laravel app from previous build stages
COPY --from=vendor /app/vendor ./vendor
COPY . .

# Create required directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

# Set permissions
RUN chmod -R 755 storage bootstrap/cache

EXPOSE 8000

# Generate Laravel key and start server
CMD php artisan key:generate --force && php artisan serve --host=0.0.0.0 --port=8000
