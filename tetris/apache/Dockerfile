FROM php:7.1-apache

RUN apt-get update \
    && apt-get install -y \
    openssl \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install opcache 

RUN a2enmod rewrite;
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY config/000-default.conf /etc/apache2/sites-enabled
COPY config/php.ini /usr/local/etc/php/

WORKDIR /var/www/back
