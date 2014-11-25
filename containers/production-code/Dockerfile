# Ushahidi platform data container
FROM dockerfile/ubuntu
MAINTAINER Robbie Mackay <robbie@ushahidi.com>

WORKDIR /data

# Clone platform code from github
ENV platform_git_head master
RUN git clone https://github.com/ushahidi/platform.git /data
RUN git checkout ${platform_git_head}

# Create and set permissions on writeable dirs
RUN mkdir -p /data/application/cache /data/application/logs /data/application/media/uploads
RUN chown -R www-data /data/application/cache /data/application/logs /data/application/media/uploads

# Make the volumes.. and hope this doesn't blat everything
RUN mkdir -p /data
VOLUME ["/data"]

RUN mkdir -p /data/application/media/uploads
VOLUME ["/data/application/media/uploads

CMD ["true"]
