FROM php:8.1-fpm-alpine

# Обновление пакетов и установка зависимостей
RUN apk update && apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    libpng-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev \
    zlib-dev \
    autoconf \
    g++ \
    make \
    gcc

# Установка PHP-расширений
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    opcache

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка зависимостей проекта
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Настройка прав
RUN chown -R www-data:www-data /var/www/html

# Используем стандартный PHP-FPM запуск
CMD ["php-fpm"]
