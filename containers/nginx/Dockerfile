# Ushahidi platform nginx container
FROM dockerfile/nginx
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

RUN mkdir -p /code
VOLUME ["/code"]

RUN mkdir -p /code/application/media/uploads
VOLUME ["/code/application/media/uploads"]

ADD sites-enabled /etc/nginx/sites-enabled
ADD conf.d /etc/nginx/conf.d
