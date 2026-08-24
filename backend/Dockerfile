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
COPY backend/ /var/www/html/DMR_project/backend/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Change Apache document root
RUN sed -i 's!/var/www/html!/var/www/html/DMR_project/backend/public!g' /etc/apache2/sites-available/000-default.conf
RUN echo "<Directory /var/www/html/DMR_project/backend/public>\n    AllowOverride All\n    Require all granted\n</Directory>" >> /etc/apache2/apache2.conf

# Give permissions
RUN chown -R www-data:www-data /var/www/html/DMR_project/backend

EXPOSE 80

# Command to start Apache (Migration is bypassed as it's already done)
CMD ["apache2-foreground"]
