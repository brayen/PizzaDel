FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpq-dev libonig-dev libxml2-dev libxslt1-dev libzip-dev \
    pkg-config \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring xml dom

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
