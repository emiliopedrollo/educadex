FROM php:7.3-fpm-alpine

RUN docker-php-ext-install bcmath

COPY . /var/www

CMD ["php-fpm"]

EXPOSE 9000
