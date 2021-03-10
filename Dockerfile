ARG PHP_VERSION=7.3
FROM php:$PHP_VERSION-cli-alpine

RUN apk add git zip unzip autoconf make g++

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /package

COPY composer.json ./

ARG LARAVEL=7
RUN composer require laravel/framework ^$LARAVEL.0

COPY . .

RUN vendor/bin/phpunit --configuration phpunit.xml
RUN vendor/bin/phpcs --standard=PSR2 -p --ignore=./tests/storage/* --report=full --report-checkstyle=build/logs/checkstyle.xml src/ tests/
