FROM php:fpm-alpine
ENV COMPOSER_ALLOW_SUPERUSER 1
WORKDIR /var/www/educadex
COPY --chown=nobody . /var/www/educadex
VOLUME /var/www/educadex
RUN docker-php-ext-install bcmath mbstring && \
    curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer && \
    composer install --no-dev --no-interaction --prefer-dist --no-cache && \
    sed -i 's/www-data/nobody/' /usr/local/etc/php-fpm.d/www.conf
CMD ["php-fpm"]

EXPOSE 9000
