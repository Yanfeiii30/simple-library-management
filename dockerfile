FROM php:8.1-apache

# Enable mysqli extension for PHP (needed for MySQL)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy all project files to Apache root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
