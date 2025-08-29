#!/bin/sh

set -e

echo "🚀 Starting Taskify container..."

echo "⚙️ Running Doctrine migrations..."
php taskify migrations:migrate --no-interaction --allow-no-migration

# Start PHP-FPM
echo "▶️ Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "▶️ Starting Nginx..."
exec nginx -g "daemon off;"
