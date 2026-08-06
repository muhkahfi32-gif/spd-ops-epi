#!/bin/bash

# Create .env if not present
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Force set APP_ENV to production and SQLite DB
sed -i 's/APP_ENV=.*/APP_ENV=production/' /var/www/html/.env
sed -i 's/APP_DEBUG=.*/APP_DEBUG=true/' /var/www/html/.env
sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' /var/www/html/.env

# Always ensure APP_KEY is generated
php artisan key:generate --force

# Prepare SQLite DB & set full permissions
touch /var/www/html/database/database.sqlite
chmod 777 /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Run migrations & seeders
php artisan migrate --force
php artisan db:seed --class=KahfiElsaOverdueSeeder --force

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Boot script auto-configuration finished."
