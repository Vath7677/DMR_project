FROM php:8.2-apache

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache URL Rewrite
RUN a2enmod rewrite headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html/DMR_project/backend

# Copy the backend code into the container
COPY . /var/www/html/DMR_project/backend/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Change Apache document root
RUN sed -i 's!/var/www/html!/var/www/html/DMR_project/backend/public!g' /etc/apache2/sites-available/000-default.conf

# Give permissions
RUN chown -R www-data:www-data /var/www/html/DMR_project/backend

EXPOSE 80

# Command to run migrations and start Apache
CMD php vendor/bin/phinx migrate -e production && apache2-foreground
