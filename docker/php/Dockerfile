FROM php:7.2-alpine

RUN apk update
RUN apk add icu-dev postgresql-dev
RUN docker-php-ext-install -j$(nproc) intl pdo_pgsql

WORKDIR /app
