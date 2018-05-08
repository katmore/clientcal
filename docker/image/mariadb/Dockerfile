# mariadb image for ClientCal
# @version 0.0.4
#
# Volumes:
# - /var/lib/mysql
# - /var/lib/clientcal-config
# - /var/lib/schema-sql
#
# Creates a volume at /var/lib/clientcal/app/config/clientcal for the clientcal config directory.
# Creates a volume at /var/lib/schema-sql for the SQL schema directory.
# Avoids host permission issues on the /var/lib/mysql volume. 
#
# This image is derived from the official 'mariadb' docker image.
# @see https://hub.docker.com/_/mariadb/
#
# Maintainer: D. Bird <doug@katmore.com>
#
FROM mariadb:10.2

LABEL \
   com.github.katmore.clientcal.docker.image.mariadb.version="0.0.4" \
   com.github.katmore.clientcal.docker.image.mariadb.mariadb-server.version="10.2" \
   maintainer="D. Bird <doug@katmore.com>" \
   description="mariadb image for ClientCal"

ARG CLIENTCAL_MYSQL_USER=clientcal
ARG CLIENTCAL_DATABASE=clientcal

ENV \
   #
   # path to clientcal-config dir
   #
   CLIENTCAL_CONFIG_DIR=/var/lib/clientcal-config \
   #
   # path to schema-sql dir
   #
   SCHEMASQL_DIR=/var/lib/schema-sql \
   #
   # mysql server user/group
   #
   MYSQL_SYSTEM_USER=mysql \
   MYSQL_SYSTEM_GROUP=mysql \
   MYSQL_SYSTEM_USER_HOME=/home/mysql \
   #
   # clientcal database/user
   #
   CLIENTCAL_DATABASE=$CLIENTCAL_DATABASE \
   CLIENTCAL_MYSQL_USER=$CLIENTCAL_MYSQL_USER

RUN \
   #
   # modify user permissions
   #   - set uid and gid to 1000 
   #     avoids permission errors on /var/lib/mysql espescially when using linux docker hosts
   #
   usermod -u 1000 $MYSQL_SYSTEM_USER &&\
   groupmod -g 1000 $MYSQL_SYSTEM_GROUP &&\
   mkdir -p $MYSQL_SYSTEM_USER_HOME &&\
   chown -R $MYSQL_SYSTEM_USER:$MYSQL_SYSTEM_GROUP /home/$MYSQL_SYSTEM_USER &&\
   #
   # prepare schema-sql dir
   #
   mkdir -p $CLIENTCAL_CONFIG_DIR &&\
   chown -R $MYSQL_SYSTEM_USER:$MYSQL_SYSTEM_GROUP $CLIENTCAL_CONFIG_DIR &&\
   #
   # prepare clientcal-config dir
   #
   mkdir -p $SCHEMASQL_DIR &&\
   chown -R $MYSQL_SYSTEM_USER:$MYSQL_SYSTEM_GROUP $SCHEMASQL_DIR &&\
   #
   # symlink /home/mysql/.my.cnf for root linux user 
   #    - the 'root' linux user should have same creds as 'mysql' linux user 
   #
   ln -s /home/$MYSQL_SYSTEM_USER/.my.cnf /root/.my.cnf &&\
   #
   # install 'jq' (command-line JSON parser)
   #
   apt-get update &&\
   apt-get install jq &&\
   apt-get clean

#
# volumes for the clientcal-config and schema-sql dirs
#
VOLUME ["$CLIENTCAL_CONFIG_DIR","$SCHEMASQL_DIR"]