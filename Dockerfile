FROM php:8.0.13-apache

RUN apt-get upgrade -y && \
    apt-get update -y --fix-missing && \
    apt-get install -y apt-utils && \
    apt-get install -y \
    libmcrypt-dev \
    zlib1g-dev \
    libzip-dev \
    curl gnupg && \
    pecl install mcrypt-1.0.4 && \
    docker-php-ext-enable mcrypt && \
    docker-php-ext-install zip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    docker-php-ext-install pdo  pdo_mysql

RUN pecl install redis && docker-php-ext-enable redis

# extensões para compressão de imagens
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# extensão para video encode
RUN apt-get install ffmpeg -y 

COPY . /var/www/html 

COPY php.ini /usr/local/etc/php/php.ini
COPY default.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/www/html

RUN chmod  -R 777 storage/*

RUN a2enmod rewrite headers ssl && \
    service apache2 restart

EXPOSE 80
