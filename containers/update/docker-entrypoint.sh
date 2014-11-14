#!/bin/bash
set -e

mkdir -p /data/application/config/environments/development/

if [ ! -f "/data/application/config/environments/development/database.php" ]; then
	cp /data/application/config/database.php /data/application/config/environments/development/database.php

	MYSQL_DATABASE=${MYSQL_DATABASE:-"ushahidi"}
	MYSQL_USERNAME=${MYSQL_USERNAME:-"root"}
	MYSQL_PASSWORD=${MYSQL_PASSWORD:-"changethisrootpassword"}

	sed -i "s/'hostname'\s*=>\s*'localhost'/'hostname' => 'db'/" /data/application/config/environments/development/database.php && \
	sed -i "s/'database'\s*=>\s*'database'/'database' => '$MYSQL_DATABASE'/" /data/application/config/environments/development/database.php && \
	sed -i "s/'username'\s*=>\s*'username'/'username' => '$MYSQL_USERNAME'/" /data/application/config/environments/development/database.php && \
	sed -i "s/'password'\s*=>\s*'password'/'password' => '$MYSQL_PASSWORD'/" /data/application/config/environments/development/database.php
fi

exec "$@"
