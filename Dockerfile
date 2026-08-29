FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

RUN docker-php-ext-install pdo_mysql

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader
