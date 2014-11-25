# Ushahidi platform data container
FROM dockerfile/ubuntu
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

RUN mkdir -p /var/lib/mysql
VOLUME ["/var/lib/mysql"]

RUN mkdir -p /data
VOLUME ["/data"]

ADD . /data

CMD ["true"]
