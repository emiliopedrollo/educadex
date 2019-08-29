FROM php:7.3-fpm-alpine
WORKDIR /var/www

RUN docker-php-ext-install bcmath

COPY . /var/www

RUN curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer

RUN composer install --no-dev

CMD ["php-fpm"]

EXPOSE 9000
