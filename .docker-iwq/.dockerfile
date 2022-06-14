FROM php:8.0.12-apache

COPY ./ /app
COPY ./.docker-iwq/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /app

RUN apt-get update
RUN apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN rm /etc/apt/preferences.d/no-debian-php
RUN apt-get install -y libxml2-dev php-soap && docker-php-ext-install soap
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite
