FROM php:fpm-alpine
WORKDIR /var/www/educadex
COPY --chown=nobody:nobody . /var/www/educadex
RUN chown nobody:nobody /var/www/educadex

RUN apk add postgresql-dev && \
    docker-php-ext-install bcmath mbstring pdo pdo_pgsql && \
    curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer && \
    sed -i 's/www-data/nobody/' /usr/local/etc/php-fpm.d/www.conf

RUN apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv

RUN apk add gnupg

USER nobody
RUN rm -fR vendor
RUN composer install --no-dev --no-cache --prefer-dist --no-interaction
RUN export APP_KEY=$(php educadex key:generate --ansi --show)

VOLUME /var/www/educadex

ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php
ENV APP_ENV production
ENV APP_DEBUG false

USER root

COPY ./docker/php-fpm/custom-entrypoint /usr/local/bin
ENTRYPOINT ["custom-entrypoint"]
CMD ["php-fpm"]

EXPOSE 9000
