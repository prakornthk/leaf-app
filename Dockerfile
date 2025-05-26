FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Remove any existing files in html directory
RUN rm -rf /var/www/html/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files first
COPY index.php ./
COPY src/ ./src/

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist

# Configure PHP-FPM
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "user = www-data" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "group = www-data" >> /usr/local/etc/php-fpm.d/www.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
