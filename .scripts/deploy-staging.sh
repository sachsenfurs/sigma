#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# reset local changes (if any)
echo "git reset --hard"
git reset --hard

echo "git clean -d -f ."
git clean -d -f .

# Pull the latest version of the app
git pull origin main

# reset vendor dir
rm -rf vendor

# Install composer dependencies
composer update
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize
php artisan icons:cache
php artisan filament:cache-components

# Compile npm assets
npm install
npm run build

# Run database migrations
php artisan migrate --force

# Exit maintenance mode
php artisan up

echo "Deployment finished!"
