FROM php:7.2-fpm

ADD https://github.com/jokkedk/webgrind/archive/master.zip /tmp

RUN mkdir -p /var/www \
    && apt-get update && apt-get install -qy graphviz unzip python \
    && unzip /tmp/master.zip -d /var/www \
    && mv /var/www/webgrind-master /var/www/webgrind \
    && pecl install xdebug-2.6.0 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.profiler_output_dir=/var/www/symfony/var/cache/xdebug" > /usr/local/etc/php/php.ini

WORKDIR /var/www/webgrind

EXPOSE 8080

CMD ["/usr/local/bin/php", "-S", "0.0.0.0:8080", "index.php"]
