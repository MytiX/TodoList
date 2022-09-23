FROM php:5.6-apache

ENV COMPOSER_HOME=/usr/local/lib/composer

RUN apt update && \
    apt install -y \
    curl \
    libcurl4-openssl-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    wget \
    zip

COPY install_composer.sh /usr/local/bin/
RUN mkdir -p $COMPOSER_HOME
RUN chown -R www-data:www-data $COMPOSER_HOME
WORKDIR /tmp
RUN /usr/local/bin/install_composer.sh

RUN docker-php-ext-install pdo pdo_mysql opcache zip curl
RUN docker-php-ext-enable opcache pdo_mysql curl

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

COPY conf/php.ini /usr/local/etc/php

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY entrypoint.sh /usr/local/bin/

RUN a2enmod rewrite

WORKDIR /var/www/

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

EXPOSE 80

CMD ["apache2-foreground"]