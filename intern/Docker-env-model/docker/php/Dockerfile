FROM php:7.2-fpm

RUN export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS"

# TODO git & xdebug for dev version only
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        git \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) opcache \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) pcntl \
    && docker-php-ext-install -j$(nproc) zip \
    # Enable internal debugging in APCu ?
    && echo "no" | pecl install apcu \
    && printf 'extension="apcu.so"\napc.shm_size="256M"\napc.enable_cli=On\n' > "$PHP_INI_DIR/conf.d/20-apcu.ini" \
    && pecl install xdebug-2.6.0 redis-4.1.1 imagick-3.4.3 \
    && docker-php-ext-enable xdebug redis imagick

# Blackfire ; Cf. https://blackfire.io/docs/integrations/docker
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && echo $version \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\nblackfire.log_level=1\nblackfire.log_file=/tmp/blackfire.log\n" > "$PHP_INI_DIR/conf.d/blackfire.ini" \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

RUN mkdir -p /tmp/blackfire \
    && curl -A "Docker" -L https://blackfire.io/api/v1/releases/client/linux_static/amd64 | tar zxp -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire /usr/bin/blackfire \
    && rm -Rf /tmp/blackfire

# Some utilities, especially for dev
RUN apt-get update \
    && apt-get -y install procps vim \
    && apt-get -y install iproute2

# TODO: we should use https://docs.docker.com/develop/develop-images/multistage-build/ and separate dev/prod builds
# in prod build, we should have the project code and run
# composer dump-autoload --optimize --no-dev --classmap-authoritative
# Cf. https://symfony.com/doc/current/performance.html#performance-optimize-composer-autoloader

ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN useradd -ms /bin/bash symfony

# Cf. https://github.com/just-containers/s6-overlay
ADD https://github.com/just-containers/s6-overlay/releases/download/v1.22.0.0/s6-overlay-amd64.tar.gz /tmp/
RUN tar xzf /tmp/s6-overlay-amd64.tar.gz -C /

ENV PHP_CLI_MEMORY_LIMIT 512M

COPY rootfs /

# (TODO composer for dev version only)
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
RUN composer.sh \
    && mv composer.phar /usr/bin/composer \
    && chmod +x /usr/bin/composer

ENTRYPOINT ["/init"]
