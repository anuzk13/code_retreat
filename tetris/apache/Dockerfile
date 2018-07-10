FROM php:7.1-apache

RUN apt-get update && \
    apt-get install -y \
    openssl \
    git \
    unzip \
    vim \
    libpq-dev \
    nano && \
    docker-php-ext-install pdo_pgsql

RUN a2enmod rewrite;
COPY config/000-default.conf /etc/apache2/sites-enabled

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
