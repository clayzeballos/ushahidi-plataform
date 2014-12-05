# Ushahidi platform PHP code container (production)
# Used to bundle the application php code

FROM dockerfile/ubuntu
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

# Add code to temp dir
ADD . /tmp/data
# Then move into /data
RUN mv /tmp/data /data
# This works around a docker bug
# https://github.com/docker/docker/issues/783#issuecomment-56013588

# Create and set permissions on writeable dirs
RUN mkdir -p /data/application/cache /data/application/logs /data/application/media/uploads
RUN chown -R www-data /data/application/cache /data/application/logs /data/application/media/uploads

WORKDIR /data

# Install composer
RUN apt-get update -y && \
    apt-get install -y curl git php5-cli && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    apt-get clean

# Install dependencies
RUN ["bin/update", "--production", "--no-migrate"]

# Make the volumes.. and hope this doesn't blat everything
VOLUME ["/data"]

CMD ["true"]
