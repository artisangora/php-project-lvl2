FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
RUN apt-get install -y libpq-dev && docker-php-ext-install pgsql pdo pdo_pgsql


RUN pecl install xdebug-2.9.5 && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD ./php.ini $PHP_INI_DIR/php.ini

WORKDIR /app