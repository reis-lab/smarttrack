FROM php:8.3-apache

# Enable MySQL support
RUN docker-php-ext-install mysqli pdo_mysql

# Enable .htaccess support
RUN a2enmod rewrite

# Copy all project files
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
