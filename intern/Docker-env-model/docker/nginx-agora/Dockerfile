FROM netapsys/rossignolb2b:latest

FROM nginx
COPY --from=0 /var/www/symfony/public /var/www/symfony/public
COPY rootfs /
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
