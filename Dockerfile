# Dockerfile for INFT1206 PHP/Apache/Xdebug
FROM php:8.2-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Enable Apache mod_rewrite (if needed for pretty URLs later)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Expose port 80 inside the container
EXPOSE 80