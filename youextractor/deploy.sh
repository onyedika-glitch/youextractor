#!/usr/bin/env bash
set -e

echo "=== Pulling latest changes from main branch ==="
git pull origin main

echo "=== Building Docker containers ==="
docker compose build

echo "=== Starting containers ==="
docker compose up -d

echo "=== Running migrations & clearing caches ==="
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

echo "=== Deployment complete! ==="
