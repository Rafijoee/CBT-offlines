FROM dunglas/frankenphp:php8.3

# Install system dependencies + composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP extensions
RUN install-php-extensions \
    gd \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip

WORKDIR /app

COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install frontend dependencies
RUN npm install
RUN npm run build

# Permission
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]