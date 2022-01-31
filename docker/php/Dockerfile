FROM php:8.0-fpm

RUN apt-get update \
    && apt-get install -y \
        libjpeg62-turbo-dev \
        libpng-dev \
        zlib1g-dev \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        git \
        zip \
        unzip \
        nginx \
    && docker-php-ext-install \
        intl \
        iconv \
        gd \
        pdo \
        pdo_mysql \
        zip \
        opcache


RUN pecl install apcu-5.1.21
RUN docker-php-ext-enable apcu

ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install xdebug
RUN apt-get update \
    && apt-get install -y libonig-dev \
    && docker-php-ext-install -j$(nproc) \
	    gettext \
	    mbstring

RUN apt-get update \
    && apt-get install -y xvfb \
            libfontconfig \
            wkhtmltopdf

RUN apt-get update \
    && apt-get install -y \
            libmagickwand-dev \
            build-essential \
            && pecl install imagick \
            && docker-php-ext-enable imagick

RUN apt-get update && apt-get -y install cron

RUN touch /var/log/xdebug.log
RUN chmod 777 /var/log/xdebug.log
RUN touch /var/log/xdebug.log
COPY nginx.conf /etc/nginx
COPY site.conf /etc/nginx/sites-enabled/default
COPY php.ini /usr/local/etc/php/php.ini
COPY entrypoint.sh /etc/entrypoint.sh
COPY onesta-cron /etc/cron.d/onesta-cron

RUN chmod 0644 /etc/cron.d/onesta-cron
RUN crontab /etc/cron.d/onesta-cron
RUN touch /var/log/cron.log
RUN chmod 0777 /etc/cron.d/onesta-cron

ENV PHP_IDE_CONFIG serverName=onesta

WORKDIR /onesta
COPY . /onesta

EXPOSE 9000 9001

ENTRYPOINT ["sh", "/etc/entrypoint.sh"]

RUN ls -la
RUN chown -R www-data:www-data /onesta
