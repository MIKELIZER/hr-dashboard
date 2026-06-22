#!/usr/bin/env bash
# exit on error
set -o errexit

echo "📦 Installing PHP Dependencies..."
composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

echo "🎨 Installing Node Dependencies and Building Assets..."
npm install
npm run build

echo "⚙️ Caching Laravel Configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗄️ Running Migrations..."
php artisan migrate --force

echo "🌱 Seeding Database..."
php artisan db:seed --force || true

echo "✅ Build completed successfully!"
