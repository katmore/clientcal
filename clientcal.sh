#!/bin/sh
# clientcal wrapper
#
ME_NAME='clientcal.sh'

##
## script localization
##
ME_SOURCE="${BASH_SOURCE[0]}"
while [ -h "$ME_SOURCE" ]; do # resolve $ME_SOURCE until the file is no longer a symlink
  ME_DIR="$( cd -P "$( dirname "$ME_SOURCE" )" && pwd )"
  ME_SOURCE="$(readlink "$ME_SOURCE")"
  [ $ME_SOURCE != /* ] && ME_SOURCE="$ME_DIR/$ME_SOURCE" # if $ME_SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done
ME_DIR="$( cd -P "$( dirname "$ME_SOURCE" )" && pwd )"
ME_NAME=$(basename "$ME_SOURCE")
ME_SHORTNAME="${ME_NAME%.*}"
ME_ABSPATH="$ME_DIR/$ME_NAME"
ME_CALLNAME=$(basename "${BASH_SOURCE[0]}")

##
## path resolutions
##
[ -n "$APP_ROOT" ] || APP_ROOT=$ME_DIR
DOCKER_COMPOSE_ROOT=$APP_ROOT/docker/compose


##
## bin wrap mode
##
#   ln -s /var/www/clientcal/bin/web-user.php /usr/bin/web-user.php &&\
#   ln -s /var/www/clientcal/bin/config-update.php /usr/bin/config-update.php &&\
#   ln -s /var/www/clientcal/bin/db-export.php /usr/bin/db-export.php &&\
#   ln -s /var/www/clientcal/bin/db-update.php /usr/bin/db-update.php

##
## reset mode
##

##
## docker-compose WRAP mode
##


##
## docker-compose START mode
##
#docker-compose run -p7081:8080 nginx



















