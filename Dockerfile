FROM php:8.0.0-fpm-alpine3.12

RUN apk --update add --no-cache \
    ${PHPIZE_DEPS} \
    libpng-dev \
    openssl-dev \
    gd \
    supervisor \
    "libxml2-dev>=2.9.10-r5" \
    git \
    "freetype>=2.10.4-r0" \
    && rm -rf /var/cache/apk/*

# Installing onigure manually to bypass vulnerability CVE-2020-26159 because the fix isn't available yet in alpine
RUN apk update && \
    apk del oniguruma && \
    wget -c https://github.com/kkos/oniguruma/releases/download/v6.9.6_rc4/onig-6.9.6-rc4.tar.gz -O - | tar -xz && \
    (cd onig-6.9.6 && ./configure && make install) && \
    rm -rf ./onig-6.9.6 && \
    rm -rf /var/cache/apk/*

RUN docker-php-ext-install \
        mbstring \
        gd \
        xml \
        tokenizer \
        ctype \
        opcache \
        && chmod -R 755 /usr/local/lib/php/extensions/ \
        && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
        && mkdir -p /app \
        && chown -R www-data:www-data /app

WORKDIR /app

COPY --chown=www-data:www-data /app/composer.json /app/composer.lock ./
COPY --chown=www-data:www-data /docker/config/ /
COPY --chown=www-data:www-data /docker/docker-entrypoint.sh /

RUN composer install --dev --no-interaction --optimize-autoloader

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
