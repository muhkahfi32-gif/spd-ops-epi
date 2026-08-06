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

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/start.sh"]
