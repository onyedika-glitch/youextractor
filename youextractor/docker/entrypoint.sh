#!/bin/sh

# Exit immediately if a command exits with a non-zero status
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
