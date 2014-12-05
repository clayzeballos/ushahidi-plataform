# Ushahidi platform cli bin/update container
FROM platform_php
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

WORKDIR /tmp

RUN apt-get update -y && \
    apt-get install -y curl git mysql-client && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    apt-get clean

RUN mkdir -p /code
VOLUME ["/code"]

COPY docker-entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

WORKDIR /code
CMD ["bin/update"]
