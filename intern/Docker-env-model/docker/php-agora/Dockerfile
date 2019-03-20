FROM netapsys/php-rossignolb2b:latest as step1
COPY . /
RUN mv /ssh ~/.ssh \
    && chmod 600 ~/.ssh/id_rsa_gitlab \
    && git clone --single-branch -b master ssh://git@gitlab.netapsys.fr:22443/netapsys/rossignolb2b.git /app \
    && rm -rf ~/.ssh/* \
    && cd /app \
    # fixme: APP_ENV=prod is not working
    && mv .env.dev .env \
    && rm -rf .git db Dockerfile Makefile docker ftp logs rossignolb2b-data Jenkinsfile* docker-compose.* var
WORKDIR /app
RUN composer install


FROM node:8 as step2
COPY --from=step1 /app /app
WORKDIR /app
RUN yarn install \
    && yarn run prod


FROM netapsys/php-rossignolb2b:latest
RUN apt-get update && apt-get install -y \
    telnet vim procps net-tools
VOLUME /var/www/symfony
COPY --from=step2 --chown=symfony /app /var/www/symfony
WORKDIR /var/www/symfony
RUN bin/console ckeditor:install \
    && bin/console assets:install public \
    && bin/console cache:warmup \
    # see https://symfony.com/doc/current/performance.html
    # fixme: --no-dev --classmap-authoritative \ is not working
    && composer dump-autoload --optimize \
    # do not run services in this container
    && rm -rf /etc/services.d/jobs-* /etc/services.d/messages-*
COPY rootfs /
