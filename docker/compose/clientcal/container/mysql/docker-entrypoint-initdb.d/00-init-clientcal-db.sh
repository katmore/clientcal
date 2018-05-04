#!/bin/bash
# 
# 
#
ME_NAME='00-init-clientcal-db.sh'

echo "($ME_NAME) MYSQL_ROOT_PASSWORD: $MYSQL_ROOT_PASSWORD"
echo "($ME_NAME) MYSQL_RANDOM_ROOT_PASSWORD: $MYSQL_RANDOM_ROOT_PASSWORD"
echo "($ME_NAME) whoami: $(whoami)"

echo -e "[client]\nuser=root\npassword=$MYSQL_ROOT_PASSWORD" > /home/mysql/.my.cnf

CLIENTCAL_PASSWORD_FILE=$CLIENTCAL_CONFIG_DIR/.mysql-password

[ -z "$CLIENTCAL_USER" ] && CLIENTCAL_USER='clientcal'
[ -z "$CLIENTCAL_DATABASE" ] && CLIENTCAL_DATABASE='clientcal'

[ -n "$CLIENTCAL_CONFIG_DIR" ] || { 
   >&2 echo "$ME_NAME: missing CLIENTCAL_CONFIG_DIR env variable"
   exit 1 
}

CLIENTCAL_PASSWORD=$(pwgen -1 32) || {
   >&2 echo "$ME_NAME: unable to generate CLIENTCAL_PASSWORD"
   exit 1
}

[ -n "$CLIENTCAL_PASSWORD" ] || {
   >&2 echo "$ME_NAME: the generated '$CLIENTCAL_PASSWORD' was empty"
   exit 1
}

echo "$CLIENTCAL_PASSWORD" > "$CLIENTCAL_PASSWORD_FILE" || {
   >&2 echo "$ME_NAME: unable to write CLIENTCAL_PASSWORD_FILE '$CLIENTCAL_PASSWORD_FILE'"
   exit 1
}

echo "GRANT ALL ON $CLIENTCAL_DATABASE.* TO '$CLIENTCAL_USER'@'%' IDENTIFIED BY '$CLIENTCAL_PASSWORD';" | mysql || {
   >&2 echo "$ME_NAME: failed to create '$CLIENTCAL_USER' user"
   exit 1
}

echo "($ME_NAME) successfully created '$CLIENTCAL_DATABASE' database"

echo "CREATE DATABASE $CLIENTCAL_DATABASE;" | mysql || {
   >&2 echo "$ME_NAME: failed to create '$CLIENTCAL_DATABASE' database"
   exit 1
}