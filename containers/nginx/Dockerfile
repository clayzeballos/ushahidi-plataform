FROM dockerfile/nginx

RUN mkdir -p /data
VOLUME ["/data"]

ADD sites-enabled /etc/nginx/sites-enabled
ADD conf.d /etc/nginx/conf.d
