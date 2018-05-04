# mariadb image for ClientCal
# @author D. Bird <doug@katmore.com>
#
# Volumes:
# - /var/lib/mysql
# - /var/lib/clientcal-config
# - /var/lib/schema-sql
#
# Creates a volume at /var/lib/clientcal/app/config/clientcal for the clientcal config directory.
# Creates a volume at /var/lib/schema-sql for the SQL schema directory.
# Avoids issues on the /var/lib/mysql volume with a user permission fix. 
#
# This image is derived from the official 'mariadb' docker image.
# @see https://hub.docker.com/_/mariadb/
FROM mariadb:10.2

LABEL \
   com.github.katmore.clientcal.docker.image.mariadb.version="0.0.1" \
   com.github.katmore.clientcal.docker.image.mariadb.mariadb-server.version="10.2" \
   com.github.katmore.clientcal.docker.image.mariadb.debian.version="8.10" \
   maintainer="D. Bird <doug@katmore.com>" \
   description="mariadb with sane permissions for the /var/lib/mysql volume"

# path for the clientcal config dir
ENV CLIENTCAL_CONFIG_DIR=/var/lib/clientcal-config
ENV SCHEMASQL_DIR=/var/lib/schema-sql

RUN \
   #
   # modify user permissions
   #   - set uid and gid to 1000 
   #     avoids permission errors on /var/lib/mysql espescially when using linux docker hosts
   #
   DB_USER=mysql &&\
   DB_GROUP=mysql &&\
   usermod -u 1000 $DB_USER &&\
   groupmod -g 1000 $DB_GROUP &&\
   mkdir -p /home/$DB_USER &&\
   chown -R $DB_USER:$DB_GROUP /home/$DB_USER &&\
   #
   # prepare schema-sql dir
   #
   mkdir -p $CLIENTCAL_CONFIG_DIR &&\
   chown -R $DB_USER:$DB_GROUP $CLIENTCAL_CONFIG_DIR &&\
   #
   # prepare clientcal-config dir
   #
   mkdir -p $SCHEMASQL_DIR &&\
   chown -R $DB_USER:$DB_GROUP $SCHEMASQL_DIR

# clientcal-config, and schema-sql dirs
VOLUME ["$CLIENTCAL_CONFIG_DIR","$SCHEMASQL_DIR"]



