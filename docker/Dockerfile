FROM php:8-cli-alpine

RUN apk update && apk add \
    build-base

RUN apk add youtube-dl libzip-dev curl-dev libxml2-dev postgresql-dev
RUN apk add --update composer
RUN docker-php-ext-install curl xml zip pcntl sockets pdo_pgsql

WORKDIR /app/
COPY . .

#RUN composer install --no-dev --classmap-authoritative

ENTRYPOINT ["php", "index.php"]