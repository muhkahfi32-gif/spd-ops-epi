FROM richarvey/nginx-php-fpm:latest

# Working directory
WORKDIR /var/www/html

# Copy all project files
COPY . /var/www/html

# Environment configuration for Nginx & PHP FPM
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install composer dependencies during build
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Copy custom Nginx configuration for Laravel routing
COPY conf/nginx/sites-available/default.conf /etc/nginx/sites-available/default.conf
COPY conf/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

# Prepare SQLite database file & seed initial data
RUN touch /var/www/html/database/database.sqlite && \
    php artisan migrate --force && \
    php artisan db:seed --class=KahfiElsaOverdueSeeder --force

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

EXPOSE 80

CMD ["/start.sh"]
