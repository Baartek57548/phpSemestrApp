FROM php:8.4-fpm
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && \
  DEBIAN_FRONTEND=noninteractive apt install -y nano vim curl git acl zip gnupg cron

RUN DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends apt-utils

RUN set -x \
    && apt-get install -y zlib1g-dev libicu-dev g++ \
    && docker-php-ext-install intl

RUN set -x \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt install -y nodejs

RUN set -x \
    && apt-get install -y libpng-dev libmcrypt-dev libxslt-dev libzip-dev \
    && pecl install mcrypt \
    && docker-php-ext-enable mcrypt

RUN set -x \
    && docker-php-ext-install xsl pdo_mysql soap zip bcmath

RUN set -x \
    && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd -with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN set -x && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

RUN apt-get update && apt-get install -y libsodium-dev
RUN docker-php-ext-install sodium

RUN set -x \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN set -x \
    && apt-get install libsodium-dev \
    && docker-php-ext-install sodium

ENV DEBIAN_FRONTEND teletype


