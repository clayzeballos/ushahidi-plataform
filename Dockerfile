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

# Make the volumes.. and hope this doesn't blat everything
VOLUME ["/data"]

CMD ["true"]
