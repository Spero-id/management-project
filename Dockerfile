# Use official PHP FPM image
FROM php:8.3-fpm

# Install system dependencies and PHP extensions needed by Laravel
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        zip \
        unzip \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j"$(nproc)" gd pdo pdo_mysql mbstring xml zip bcmath intl opcache \
    && rm -rf /var/lib/apt/lists/*

# Install Composer (copy from official Composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first to leverage Docker cache (composer.lock may or may not exist)
# using a glob so it doesn't fail when composer.lock is absent.
COPY composer.* ./

# Install PHP dependencies without running scripts (scripts may depend on app files
# which are not yet copied into the image). We'll run dump-autoload later.
RUN composer install --no-interaction --prefer-dist --no-scripts --optimize-autoloader

# Copy application source
COPY . .

# Ensure storage and cache directories exist (some packages expect these during discovery)
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Run autoload optimization but do not run Composer scripts here (avoids calling artisan during build)
RUN composer dump-autoload --optimize --no-scripts

# Ensure storage and cache directories are writable
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the port used by `php artisan serve` in docker-compose (8340)
EXPOSE 8340

CMD ["php-fpm"]
