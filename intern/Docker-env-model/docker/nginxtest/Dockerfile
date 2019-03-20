FROM nginx

COPY symfony.conf /etc/nginx/conf.d/default.conf
COPY upload.conf /etc/nginx/conf.d/upload.conf


ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
