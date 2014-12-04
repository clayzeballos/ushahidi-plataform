# Ushahidi platform nginx container
FROM dockerfile/nginx
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

RUN mkdir -p /data
VOLUME ["/data"]

RUN mkdir -p /data/application/media/uploads
VOLUME ["/data/application/media/uploads"]

ADD sites-enabled /etc/nginx/sites-enabled
ADD conf.d /etc/nginx/conf.d
