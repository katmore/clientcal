#!/bin/bash
# Performs various MySQL database preparations for ClientCal.  
# - create 'clientcal' MySQL user
# - save MySQL root password to /home/mysql/my.cnf
#
# @author D. Bird <doug@katmore.com>
#

# script localization
ME_NAME='00-prepare-mysql.sh'

# mysql console command
MYSQL_CMD=${mysql[@]}
[ -n "$MYSQL_CMD" ] || MYSQL_CMD=mysql

# path to mysql system user home dir (/home/mysql)
[ -n "$MYSQL_SYSTEM_USER_HOME" ] || MYSQL_SYSTEM_USER_HOME=/home/mysql

# path to cilentcal-config dir
[ -n "$CLIENTCAL_CONFIG_DIR" ] || CLIENTCAL_CONFIG_DIR=/var/lib/clientcal-config

# clientcal mysql user
[ -n "$CLIENTCAL_MYSQL_USER" ] || CLIENTCAL_MYSQL_USER='clientcal'

# clientcal database name
[ -n "$CLIENTCAL_DATABASE" ] || CLIENTCAL_DATABASE='clientcal'

# enforce sanity of mysql console command
echo 'SELECT version() as version;' | $MYSQL_CMD > /dev/null || {
   >&2 echo "$ME_NAME: mysql command '$MYSQL_CMD' failed with exit status $?"
   exit 1
}

# enforce sanity of mysql system user home dir
[ -d "$MYSQL_SYSTEM_USER_HOME" ] || {
   >&2 echo "$ME_NAME: mysql system user home dir '$MYSQL_SYSTEM_USER_HOME' does not exist"
   exit 1
}

# enforce sanity of root mysql user password
[ -n "$MYSQL_ROOT_PASSWORD" ] || {
   >&2 echo "$ME_NAME: MYSQL_ROOT_PASSWORD was empty"
   exit 1
}

# generate clientcal mysql user password
CLIENTCAL_MYSQL_PASSWORD=$(pwgen -1 32) || {
   >&2 echo "$ME_NAME: unable to generate CLIENTCAL_MYSQL_PASSWORD"
   exit 1
}

# enforce sanity of clientcal mysql user password
[ -n "$CLIENTCAL_MYSQL_PASSWORD" ] || {
   >&2 echo "$ME_NAME: the generated CLIENTCAL_MYSQL_PASSWORD was empty"
   exit 1
}

# write mysql root user password to /home/mysql/.my.cnf
printf "[client]\nuser=root\npassword=$MYSQL_ROOT_PASSWORD" > $MYSQL_SYSTEM_USER_HOME/.my.cnf || {
   >&2 echo "$ME_NAME: failed to write '$MYSQL_SYSTEM_USER_HOME/.my.cnf'"
   exit 1
}

# write clientcal mysql password to /var/lib/clientcal-config/.mysql-password
printf "$CLIENTCAL_MYSQL_PASSWORD" > $CLIENTCAL_CONFIG_DIR/.mysql-password || {
   >&2 echo "$ME_NAME: failed to write '$CLIENTCAL_CONFIG_DIR/.mysql-password'"
   exit 1
}

# create clientcal mysql user
echo "GRANT ALL ON $CLIENTCAL_DATABASE.* TO '$CLIENTCAL_MYSQL_USER'@'%' IDENTIFIED BY '$CLIENTCAL_MYSQL_PASSWORD';" | $MYSQL_CMD || {
   >&2 echo "$ME_NAME: failed to create '$CLIENTCAL_MYSQL_USER' user"
   exit 1
}
echo "($ME_NAME) created '$CLIENTCAL_MYSQL_USER' mysql user with password: $CLIENTCAL_MYSQL_PASSWORD"

# create clientcal database
echo "CREATE DATABASE $CLIENTCAL_DATABASE;" | $MYSQL_CMD || {
   >&2 echo "$ME_NAME: failed to create '$CLIENTCAL_DATABASE' mysql database"
   exit 1
}
echo "($ME_NAME) created '$CLIENTCAL_DATABASE' mysql database"
