FROM php:8.4-fpm-alpine3.22

WORKDIR /var/www/html

RUN apk update && apk upgrade

RUN apk add --no-cache \
    curl \
    zip \
    unzip \
    linux-headers \
    autoconf \
    g++ \
    make \
    fcgi \
    && pecl install xdebug apcu \
    && docker-php-ext-enable xdebug apcu \
    && echo "xdebug.mode=coverage,develop" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm -rf /var/lib/apk/lists/* \
    && rm -rf /var/cache/apk/*

# prepara o endpoint de healthcheck:
RUN echo "ping.path = /ping" >> "/usr/local/etc/php-fpm.d/www.conf"
RUN echo "ping.response = pong" >> "/usr/local/etc/php-fpm.d/www.conf"

RUN echo "apc.enabled=1" >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini \
    && echo "apc.shm_size=32M" >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini \
    && echo "apc.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini
# como testar manualmente:
# > docker exec -it {service-name} sh
# SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./docker/containers/php/setup.sh /usr/local/bin/setup.sh

RUN chmod +x /usr/local/bin/setup.sh

COPY . .

ENTRYPOINT ["/usr/local/bin/setup.sh"]
