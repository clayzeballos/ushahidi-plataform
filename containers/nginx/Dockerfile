# Ushahidi platform nginx container
FROM dockerfile/nginx
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

RUN mkdir -p /data
VOLUME ["/data"]

ADD sites-enabled /etc/nginx/sites-enabled
ADD conf.d /etc/nginx/conf.d
