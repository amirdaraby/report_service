FROM php:8.5-fpm 

ARG WWW_USER

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    unzip \
    libzip-dev \
    libicu-dev \
    libtool \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install exif \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


RUN curl -fL --output /usr/local/bin/pie https://github.com/php/pie/releases/latest/download/pie.phar \
    && chmod +x /usr/local/bin/pie

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN pie install pie-extensions/redis

WORKDIR /app

RUN mkdir -p /app/storage/framework/cache /app/storage/framework/sessions /app/storage/framework/views /app/storage/logs \
    && chown -R ${WWW_USER}:${WWW_USER} /app/storage
