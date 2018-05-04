# php image for ClientCal
# @author D. Bird <doug@katmore.com>
#
# Entrypoint: /var/local/docker-entrypoint.sh
#
# Default configuration:
# - The entrypoint starts and monitors php-fpm.
# - Php-fpm displays all PHP errors to STERR (seen when attached to container).
# - Php-fpm listens on all available interfaces to port 9000.
#
# Volumes:
# - /etc/php7
# - /var/www/clientcal/app
# - /var/www/clientcal/bin
# - /var/www/clientcal/lib
# - /var/www/clientcal/src
# - /var/www/clientcal/web
#
# The '/etc/php7' volume can be used to change php and php-fpm configuration while still using the default entrypoint included in this image.
# The /var/www/clientcal/ volumes ('app', 'bin', 'lib', 'src', and 'web') allow for host managed source code changes. 
#
# PHP 7.2 is installed with php-fpm and all php extensions as required by ClientCal.
# The ClientCal source code and composer managed dependencies are installed.
#
# The 'codecasts' PHP APK Repository for Alpine Linux is the source of php related packages.
# @see https://github.com/codecasts/php-alpine
#
# The official 'getcomposer.org' installer is used to install composer. 
# @see https://getcomposer.org/installer
#
# This image is derived from the official 'alpine' docker image. 
# @see https://hub.docker.com/_/alpine/
FROM alpine:3.7

LABEL \
   com.github.katmore.clientcal.docker.image.php-fpm.version="0.0.3" \
   maintainer="D. Bird <doug@katmore.com>" \
   description="php-fpm and all php modules required by 'clientcal' on alpine linux"

ENV APP_USER=clientcal
ENV APP_GROUP=clientcal
ENV APP_USER_HOME=/var/www
ENV APP_ROOT=$APP_USER_HOME/clientcal
ENV DOCUMENT_ROOT=$APP_ROOT/web
ENV ENV="/root/.ashrc"

ADD https://php.codecasts.rocks/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub

RUN \
   #
   # add 'codecasts' PHP APK repository
   # 
   echo "@php https://php.codecasts.rocks/v3.7/php-7.2" >> /etc/apk/repositories &&\
   #
   # install apk packages
   #
   apk --update add \
      #
      # php dependency packages
      #
      php@php \
      php-common@php \
      php-fpm@php \
      php-phar@php \
      php-json@php \
      php-iconv@php \
      php-mbstring@php \
      php-imagick@php \
      php-soap@php \
      php-mysqli@php \
      php-pdo_mysql@php \
      php-openssl@php \
      php-zlib@php \
      php-session@php \
      php-ctype@php \
      &&\
      #
      # other system dependency packages
      #
      #bash
   #
   # global php binary (via symlink)
   #
   ln -s /usr/bin/php7 /usr/bin/php &&\
   #
   # prepare app user and group with home dir and proper permissions
   #
   addgroup -g 1000 -S $APP_GROUP &&\
   adduser -D -h $APP_USER_HOME -G $APP_GROUP -s /bin/sh -u 1000 $APP_USER &&\
   mkdir -p $APP_USER_HOME &&\
   mkdir -p $APP_ROOT &&\
   chown -R $APP_USER:$APP_GROUP $APP_USER_HOME &&\
   chown -R $APP_USER:$APP_GROUP $APP_ROOT &&\
   ln -s /var/www/clientcal/bin/clientcal-user.php /usr/bin/clientcal-user.php &&\
   ln -s /var/www/clientcal/bin/config-update.php /usr/bin/config-update.php &&\
   ln -s /var/www/clientcal/bin/db-export.php /usr/bin/db-export.php &&\
   ln -s /var/www/clientcal/bin/db-update.php /usr/bin/db-update.php

# copy ClientCal source code
COPY ./app $APP_ROOT/app
COPY ./bin $APP_ROOT/bin
COPY ./lib $APP_ROOT/lib
COPY ./src $APP_ROOT/src
COPY ./web $APP_ROOT/web
COPY composer.json $APP_ROOT/composer.json


#
RUN \
   apk --update add \
      curl \
      git \
      &&\
   COMPOSER_INSTALLER_URL="https://getcomposer.org/installer" &&\
   COMPOSER_INSTALLER_SHA384="544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061" &&\
   COMPOSER_INSTALLER_PATH=$(mktemp) &&\
   curl -sL $COMPOSER_INSTALLER_URL -o $COMPOSER_INSTALLER_PATH &&\
   php -r "if (hash_file('SHA384', \"$COMPOSER_INSTALLER_PATH\") !== \"$COMPOSER_INSTALLER_SHA384\") { echo \"hash mismatch for $COMPOSER_INSTALLER_URL\"; exit(1); }" &&\
   php $COMPOSER_INSTALLER_PATH -- --install-dir=/usr/bin --filename=composer &&\
   su - $APP_USER -c "cd $APP_ROOT && composer update" &&\
   rm -f /usr/bin/composer &&\
   apk del \
      curl \
      git \
      &&\
   rm /var/cache/apk/*
   
COPY ./docker/image/php/php-fpm.conf /etc/php7/php-fpm.conf
COPY ./docker/image/php/docker-entrypoint.sh /var/local/docker-entrypoint.sh

# /etc/php7 volume allows for host customized php configuration
# /var/www/clientcal/ volumes ('app', 'bin', 'lib', 'src', and 'web') allow for host managed source code changes
VOLUME ["/etc/php7","/var/www/clientcal/app","/var/www/clientcal/bin","/var/www/clientcal/lib","/var/www/clientcal/src","/var/www/clientcal/web"]

# php-fpm port
EXPOSE 9000
ENTRYPOINT [ "/var/local/docker-entrypoint.sh" ]