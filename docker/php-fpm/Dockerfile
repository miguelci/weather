FROM php:7.4-fpm-buster AS base

RUN mkdir -p /usr/share/php-fpm \
    && apt-get update \
    && apt-get install -y \
        git \
        libzip-dev \
        unzip \
        php7.3-sqlite \
        cron \
    && docker-php-ext-install \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

EXPOSE 9000

COPY docker/php-fpm/command.cron /etc/cron.d/predictions.cron
RUN chmod 0644 /etc/cron.d/predictions.cron \
    && crontab /etc/cron.d/predictions.cron \
    && touch /var/log/cron.log

COPY docker/php-fpm/starter.sh /starter.sh
RUN ["chmod", "+x", "/starter.sh"]
CMD ["/starter.sh"]

FROM base as dev

RUN composer global require hirak/prestissimo
COPY composer.* ./

FROM base as init

COPY docker/php-fpm/entrypoint.sh /entrypoint.sh
RUN ["chmod", "+x", "/entrypoint.sh"]
CMD ["/entrypoint.sh"]
