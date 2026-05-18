FROM dunglas/frankenphp:php8.3

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

RUN composer install --optimize-autoloader --no-dev

RUN npm install && npm run build

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]