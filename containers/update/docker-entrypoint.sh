#!/bin/bash
set -e

mkdir -p /code/application/config/environments/development/

MYSQL_DATABASE=${MYSQL_DATABASE:-"ushahidi"}
MYSQL_USERNAME=${MYSQL_USERNAME:-"root"}
MYSQL_PASSWORD=${MYSQL_PASSWORD:-"changethisrootpassword"}

export MYSQL_DATABASE MYSQL_USERNAME MYSQL_PASSWORD

if [ ! -f "/code/application/config/environments/development/database.php" ]; then
	cp /code/application/config/database.php /code/application/config/environments/development/database.php

	sed -i "s/'hostname'\s*=>\s*'localhost'/'hostname' => 'db'/" /code/application/config/environments/development/database.php && \
	sed -i "s/'database'\s*=>\s*'database'/'database' => '$MYSQL_DATABASE'/" /code/application/config/environments/development/database.php && \
	sed -i "s/'username'\s*=>\s*'username'/'username' => '$MYSQL_USERNAME'/" /code/application/config/environments/development/database.php && \
	sed -i "s/'password'\s*=>\s*'password'/'password' => '$MYSQL_PASSWORD'/" /code/application/config/environments/development/database.php
fi

exec "$@"
