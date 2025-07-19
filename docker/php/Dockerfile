# --- Base Build Stage: install system deps & composer deps -------------------
FROM php:8.2-fpm-alpine AS build

# System packages
RUN apk add --no-cache \
    bash git unzip libzip-dev libpng-dev icu-dev oniguruma-dev \
    libxml2-dev mysql-client

# PHP extensions (add as needed)
RUN docker-php-ext-install pdo pdo_mysql zip intl gd

# (Optional) opcache for prod-like behavior in dev
RUN docker-php-ext-install opcache

# Copy composer (official installer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy composer manifests first (layer caching)
COPY composer.json composer.lock ./

# Install dependencies (no dev optimize? we’ll do install --no-interaction)
RUN composer install --no-interaction --prefer-dist --no-dev --no-scripts || true

# Now copy the full app
COPY . .

# Re-run composer install including dev now that full source present
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate optimized autoload (prod-ish)
RUN composer dump-autoload

# --- Runtime Stage ----------------------------------------------------------
FROM php:8.2-fpm-alpine AS app

# System packages
RUN apk add --no-cache bash icu-libs libpng libzip tzdata

# Copy compiled PHP extensions from build stage (auto since same base, but safe)
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Copy application code
WORKDIR /var/www/html
COPY --from=build /var/www/html /var/www/html

# Ensure storage + bootstrap/cache writable
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]