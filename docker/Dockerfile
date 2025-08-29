# Stage 1: Composer deps
FROM composer:2 AS vendor
WORKDIR /app
COPY src/composer.json src/composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Node build
FROM node:18-alpine AS frontend
WORKDIR /app
COPY src/package.json src/package-lock.json ./
RUN npm install
COPY src/ ./
RUN npm run build

# Stage 3: Final PHP + Nginx
FROM php:8.3-fpm-alpine

# Install system deps
RUN apk add --no-cache \
    git unzip curl icu-dev libpng-dev libxml2-dev oniguruma-dev bash nginx supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql intl gd

WORKDIR /var/www

# Copy application code
COPY src/ ./

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Copy Composer vendor
COPY --from=vendor /app/vendor ./vendor

# Copy built frontend
COPY --from=frontend /app/public/build ./public/build

# Copy Nginx + entrypoint
COPY docker/nginx/prod.conf /etc/nginx/nginx.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]
