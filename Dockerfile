FROM php:8.5-cli

RUN docker-php-ext-install pdo_mysql

COPY ./test_work_php/configs/php.ini /usr/local/etc/php/conf.d/99-app.ini
