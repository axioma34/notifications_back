FROM php:7.4-fpm
ENV DATABASE_URL="mysql://root:root@test_db:3306/test_db"

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get update && \
apt-get install -y \
libzip-dev

RUN apt-get update && apt-get install -y wget

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu

WORKDIR /usr/src/app

COPY --chown=1000:1000 / /usr/src/app

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-scripts --prefer-dist --no-interaction
CMD dockerize --wait tcp://db:3306 --timeout 30s /usr/src/app/start.sh; php-fpm
