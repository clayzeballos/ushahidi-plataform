# Ushahidi platform data container
# Used to persist database and upload data
# when other containers are rebuilt

FROM dockerfile/ubuntu
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

RUN mkdir -p /var/lib/mysql
VOLUME ["/var/lib/mysql"]

RUN mkdir -p /code/application/media/uploads
VOLUME ["/code/application/media/uploads"]

CMD ["true"]
