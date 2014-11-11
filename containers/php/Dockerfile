FROM dockerfile/ubuntu

# This should be replaced by an official php-fpm container soon..

# Install PHP-FPM and required extensions
RUN apt-get update -y && \
    apt-get install -y \
    php5-fpm \
    php5-cli \
    php5-curl \
    php5-gd \
    php5-json \
    php5-imap \
    php5-mcrypt \
    php5-mysqlnd

# Configure PHP-FPM
RUN sed -i "s/;date.timezone =.*/date.timezone = UTC/" /etc/php5/fpm/php.ini && \
    sed -i "s/display_errors = Off/display_errors = stderr/" /etc/php5/fpm/php.ini && \
    sed -i -e "s/upload_max_filesize\s*=\s*2M/upload_max_filesize = 100M/g" /etc/php5/fpm/php.ini && \
    sed -i -e "s/post_max_size\s*=\s*8M/post_max_size = 100M/g" /etc/php5/fpm/php.ini && \
    sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php5/fpm/php-fpm.conf && \
    sed -i '/^listen = /clisten = 9000' /etc/php5/fpm/pool.d/www.conf && \
    sed -i '/^listen.allowed_clients/c;listen.allowed_clients =' /etc/php5/fpm/pool.d/www.conf && \
    sed -i '/^;catch_workers_output/ccatch_workers_output = yes' /etc/php5/fpm/pool.d/www.conf

RUN mkdir -p /data
VOLUME ["/data"]

EXPOSE 9000

CMD ["/usr/sbin/php5-fpm", "-F"]
