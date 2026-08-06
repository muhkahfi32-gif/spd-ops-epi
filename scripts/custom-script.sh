#!/bin/bash

# Ensure SQLite database exists and is writable
touch /var/www/html/database/database.sqlite
chmod 777 /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel setup commands on boot
php artisan migrate --force
php artisan db:seed --class=KahfiElsaOverdueSeeder --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Custom boot script completed successfully."
