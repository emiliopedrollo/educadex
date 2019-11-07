FROM php:fpm-alpine
ENV COMPOSER_ALLOW_SUPERUSER 1
WORKDIR /var/www/educadex
COPY --chown=nobody . /var/www/educadex
VOLUME /var/www/educadex
RUN apk add postgresql-dev && \
    docker-php-ext-install bcmath mbstring pdo pdo_pgsql && \
    curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer && \
    sed -i 's/www-data/nobody/' /usr/local/etc/php-fpm.d/www.conf

RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv

USER nobody
RUN echo "APP_KEY=" >> .env && \
    composer install --no-dev --no-cache --prefer-dist --no-interaction && \
    php educadex key:generate --ansi

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php
ENV APP_ENV production
ENV APP_DEBUG false

USER root
CMD ["php-fpm"]

EXPOSE 9000
