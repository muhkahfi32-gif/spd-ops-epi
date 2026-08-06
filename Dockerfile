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
ENV LOG_CHANNEL stderr

# Install composer dependencies during build
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Copy custom Nginx configuration for Laravel routing
COPY conf/nginx/sites-available/default.conf /etc/nginx/sites-available/default.conf
COPY conf/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf

# Custom boot script setup
COPY scripts/custom-script.sh /var/www/html/scripts/custom-script.sh
RUN chmod +x /var/www/html/scripts/custom-script.sh

# Prepare storage directories and database file
RUN mkdir -p /var/www/html/storage/framework/views \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    /var/www/html/database

RUN touch /var/www/html/database/database.sqlite
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

EXPOSE 80

CMD ["/start.sh"]
