#!/bin/bash
set -e

mkdir -p /data/application/config/environments/development/

if [ ! -f "/data/application/config/environments/development/database.php" ]; then
	cp /root/template/database.php /data/application/config/environments/development/database.php
fi

exec "$@"
