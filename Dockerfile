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

# Prepare SQLite database file
RUN touch /var/www/html/database/database.sqlite

# Set directory permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

EXPOSE 80

CMD ["/start.sh"]
