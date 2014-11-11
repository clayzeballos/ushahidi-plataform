FROM platform_php

WORKDIR /tmp

RUN apt-get update -y && \
    apt-get install -y curl git && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    apt-get remove --purge curl -y && \
    apt-get clean

RUN mkdir -p /data
VOLUME ["/data"]

WORKDIR /data

CMD ["bin/update"]
