FROM serversideup/php:8.2-fpm-nginx

# Set the working directory
WORKDIR /var/www/html

# Copy your project files into the container
COPY --chown=www-data:www-data . .

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader

# FIX: Give the web server permission to read the vendor packages
RUN chown -R www-data:www-data /var/www/html/vendor

# Ensure the entire storage and public directories are readable by the web server
RUN chmod -R 755 /var/www/html/storage /var/www/html/public

# Set the web root to Laravel's public directory
ENV AUTORUN_ENABLED=true
ENV WEB_DOCUMENT_ROOT=/var/www/html/public