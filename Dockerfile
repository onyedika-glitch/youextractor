FROM node:18-alpine as frontend-builder

WORKDIR /app

# Copy package files
COPY package.json package-lock.json* .npmrc* ./
COPY youextractor/package.json youextractor/package-lock.json* youextractor/.npmrc* ./youextractor/

# Install dependencies
RUN npm install

# Build (if there's a build script)
RUN npm run build 2>/dev/null || true

# PHP stage
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    postgresql-client \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && rm -rf /var/cache/apk/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy project files
COPY youextractor/ /app/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate Laravel app key if not set
RUN php artisan key:generate --force || true

# Create necessary directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

# Set permissions
RUN chown -R nobody:nobody /app/storage /app/bootstrap/cache

# Expose port
EXPOSE 8000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app/public"]
