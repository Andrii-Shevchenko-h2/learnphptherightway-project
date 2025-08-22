# Use the official PHP 8.2 FPM image on Alpine Linux for a small footprint
FROM php:8.2-fpm-alpine

# Set the working directory
WORKDIR /var/www/html

# Install common PHP extensions
# Feel free to add or remove extensions as needed for your project
RUN docker-php-ext-install pdo pdo_mysql opcache

# Install composer for dependency management
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ensure the web server user owns the files
# The default user for php-fpm is www-data
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

