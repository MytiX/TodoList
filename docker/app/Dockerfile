FROM php:8.0.24-apache

ENV COMPOSER_HOME=/usr/local/lib/composer

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list

RUN apt update && \
    apt install -y \
    curl \
    libcurl4-openssl-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    wget \
    zip \
    symfony-cli

# On copy l'executable composer depuis l'image officiel depuis la version 2.2.17
# Attention en version 2.4 les scripts post install ne fonctionne pas
COPY --from=composer/composer:2.2.17 /usr/bin/composer /usr/local/bin/composer
RUN mkdir -p $COMPOSER_HOME
RUN chown -R www-data:www-data $COMPOSER_HOME

RUN pecl install xdebug
RUN docker-php-ext-install pdo pdo_mysql opcache zip curl
RUN docker-php-ext-enable opcache pdo_mysql curl xdebug

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

COPY conf/php.ini /usr/local/etc/php

COPY conf/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY entrypoint.sh /usr/local/bin/

RUN a2enmod rewrite

WORKDIR /var/www/

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

EXPOSE 80

ENV CODACY_PROJECT_TOKEN=c986cc7e72874c1d9024ffa49edb3591

CMD ["apache2-foreground"]