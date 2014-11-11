FROM platform_php

WORKDIR /tmp

RUN apt-get update -y && \
    apt-get install -y curl git mysql-client && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    apt-get clean

RUN mkdir -p /data
VOLUME ["/data"]

WORKDIR /data

CMD ["bin/update"]
