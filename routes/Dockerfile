# Gunakan PHP image dengan Composer dan extensions
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd

# Set working directory
WORKDIR /var/www

# Copy composer dan install dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Jalankan Laravel
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
