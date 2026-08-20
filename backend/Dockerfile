FROM php:8.2-apache

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache URL Rewrite (for Slim Framework)
RUN a2enmod rewrite headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer