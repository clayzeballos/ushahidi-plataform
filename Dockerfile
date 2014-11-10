FROM orchardup/php5
RUN apt-get install php5-json -y
ADD . /code