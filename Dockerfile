FROM php:8.2.10-apache

RUN apt-get update -y
RUN apt-get install libyaml-dev librdkafka-dev zip unzip libzip-dev -y
RUN pecl install yaml && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini && docker-php-ext-enable yaml
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install rdkafka && docker-php-ext-enable rdkafka
RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY composer.json .
COPY composer.phar .

RUN php composer.phar install

# Disable the default site.
RUN a2dissite 000-default.conf
# Enable the mod rewrite for my config.
RUN a2enmod rewrite
# Put my own conf in place.
COPY MyWebConf.conf /etc/apache2/sites-enabled/
COPY html/ /var/www/html/
