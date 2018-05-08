# php image for ClientCal using apline linux
# @version 0.0.5
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
#
ARG ALPINE_VERSION=3.7
#
# Maintainer: D. Bird <doug@katmore.com>
#
FROM alpine:$ALPINE_VERSION

#
# PHP version
#
ARG PHP_MAJOR_VERSION=7
ARG PHP_MINOR_VERSION=2

#
# ClientCal application basename
#
ARG APP_BASENAME=clientcal
ARG APP_USER_HOME=/var/www


#
# ClientCal application user/group and home dir
#
ENV \
   APP_USER=$APP_BASENAME \
   APP_GROUP=$APP_BASENAME \
   APP_USER_HOME=$APP_USER_HOME \
   APP_ROOT=$APP_USER_HOME/$APP_BASENAME 

ENV \   
   #
   # ClientCal application web document root dir
   #
   DOCUMENT_ROOT=$APP_ROOT/web \
   #
   # php-fpm
   #
   FPM_DAEMON=php-fpm$PHP_MAJOR_VERSION \
   FPM_CONF=/etc/php$PHP_MAJOR_VERSION/php-fpm.conf \
   FPM_PORT=9000 \
   START_FPM_DAEMON=0
   
   
LABEL \
   com.github.katmore.clientcal.docker.image.php.version="0.0.5" \
   com.github.katmore.clientcal.docker.image.php.php-major-version=$PHP_MAJOR_VERSION \
   com.github.katmore.clientcal.docker.image.php.php-minor-version=$PHP_MINOR_VERSION \
   com.github.katmore.clientcal.docker.image.php.alpine-version=$ALPINE_VERSION \
   maintainer="D. Bird <doug@katmore.com>" \
   description="php image for ClientCal using apline linux"
#
# system dependencies for php, composer, and ClientCal
#
RUN \
   #
   # php composer installer params
   #
   COMPOSER_INSTALLER_URL="https://getcomposer.org/installer" &&\
   COMPOSER_INSTALLER_SHA384="544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061" &&\
   #
   # PHP Repository - Name, URL 
   # @link https://github.com/codecasts/php-alpine (PHP Repositories for Alpine - by CODECASTS)
   #
   PHP_REPO_NAME="php-alpine" &&\
   PHP_REPO_URL="https://php.codecasts.rocks/v3.7/php-$PHP_MAJOR_VERSION.$PHP_MINOR_VERSION" &&\
   #
   # PHP Repository - RSA Public Key
   # @link https://php.codecasts.rocks/php-alpine.rsa.pub
   #
   PHP_REPO_PUBKEY_RSA="" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"-----BEGIN PUBLIC KEY-----\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvJHIjkW9Qo+aST95YwH5\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"dBgehdjcbacrO8TMquvHoGoa9yGHHnWmCxA2BtRoUcOPphjpY5bRVi5y179cheps\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"gmswORIFqFz8Nj0if/zOXTs+7sQ3Tq+cdtiuFM+u+ezAwWdPWgt8ZHF4BVoNpHrd\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"B4uxR8+23Ie24HAFMIsxNLI6erRrDvGvBmmv+iapyxkb/jrAb1vRjqc5Cu9Dq1aH\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"YchYhV9/Fp9dto9nZ6LG7LM0LA9pDu9eG1GavvRB7NDMyInudJyv/lbDYH0a6asJ\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"Af/aLz05NBSMtTYArJd9gZ0meYJVYCDACWPHKxsa/PXK0h9sVhXWSbKKritFLN6+\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"/wIDAQAB\n" &&\
   PHP_REPO_PUBKEY_RSA=$PHP_REPO_PUBKEY_RSA"-----END PUBLIC KEY-----\n" &&\
   #
   # php-fpm params
   #
   FPM_PIDFILE=/var/run/php-fpm$PHP_MAJOR_VERSION.pid &&\
   #
   # add PHP repository and public key
   # 
   echo "@php $PHP_REPO_URL" >> /etc/apk/repositories &&\
   printf "$PHP_REPO_PUBKEY_RSA" > /etc/apk/keys/$PHP_REPO_NAME.rsa.pub &&\
   #
   # apk packages
   #
   apk --update add \
      #
      # ca root certificates
      #
      ca-certificates \
      #
      # php base 
      #
      php@php \
      php-common@php \
      php-fpm@php \
      php-json@php \
      #
      # php extension dependencies for ClientCal
      #
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
      #
      # composer dependencies
      #
      php-phar@php \
      curl \
      git \
   &&\
   #
   # apk cache cleanup
   #
   rm /var/cache/apk/* &&\   
   #
   # global php binary symlink
   #
   ln -s /usr/bin/php$PHP_MAJOR_VERSION /usr/bin/php &&\
   #
   # install composer
   #
   COMPOSER_INSTALLER_PATH=$(mktemp) &&\
   curl -sL $COMPOSER_INSTALLER_URL -o $COMPOSER_INSTALLER_PATH &&\
   php -r "if (hash_file('SHA384', \"$COMPOSER_INSTALLER_PATH\") !== \"$COMPOSER_INSTALLER_SHA384\") { echo \"hash mismatch for $COMPOSER_INSTALLER_URL\"; exit(1); }" &&\
   php $COMPOSER_INSTALLER_PATH -- --install-dir=/usr/bin --filename=composer &&\
   #
   # prepare app user, app group, app user home dir, app root dir 
   #
   addgroup -g 1000 -S $APP_GROUP &&\
   adduser -D -h $APP_USER_HOME -G $APP_GROUP -s /bin/sh -u 1000 $APP_USER &&\
   mkdir -p $APP_USER_HOME &&\
   mkdir -p $APP_ROOT &&\
   chown -R $APP_USER:$APP_GROUP $APP_USER_HOME &&\
   chown -R $APP_USER:$APP_GROUP $APP_ROOT &&\
   #
   # create php-fpm.conf
   # 
   echo ";;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;" > $FPM_CONF &&\
   echo ";; FPM Configuration for clientcal/docker/images/php      ;;" >> $FPM_CONF &&\
   echo ";; includes single [www] pool configuration               ;;" >> $FPM_CONF &&\
   echo ";;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;" >> $FPM_CONF &&\
   echo "[global]" >> $FPM_CONF &&\
   echo "pid = $FPM_PIDFILE" >> $FPM_CONF &&\
   echo "error_log = /proc/self/fd/2" >> $FPM_CONF &&\
   echo "[www]" >> $FPM_CONF &&\
   echo "pm = dynamic" >> $FPM_CONF &&\
   echo "pm.max_children = 5" >> $FPM_CONF &&\
   echo "pm.start_servers = 2" >> $FPM_CONF &&\
   echo "pm.min_spare_servers = 1" >> $FPM_CONF &&\
   echo "pm.max_spare_servers = 3" >> $FPM_CONF &&\
   echo "user = $APP_USER" >> $FPM_CONF &&\
   echo "group = $APP_GROUP" >> $FPM_CONF &&\
   echo "listen = $FPM_PORT" >> $FPM_CONF &&\
   echo "catch_workers_output = yes" >> $FPM_CONF 
   
#
# php-fpm port
#
EXPOSE 9000

# 
# PHP Configruation Volume (allows host customized php configuration)
# - /etc/php7 
#
VOLUME ["/etc/php$PHP_MAJOR_VERSION"]
   
#
# copy ClientCal source code
#
COPY ./app $APP_ROOT/app
COPY ./bin $APP_ROOT/bin
COPY ./lib $APP_ROOT/lib
COPY ./src $APP_ROOT/src
COPY ./web $APP_ROOT/web
COPY composer.json $APP_ROOT/composer.json

#
# Prepare the ClientCal application
#
RUN \
   #
   # paths for "clientcal command utility" 
   #
   CLIENTCAL_CMD_SOURCE_PATH=$APP_ROOT/clientcal.sh &&\
   CLIENTCAL_CMD_INSTALL_PATH=/usr/bin/clientcal &&\
   CLIENTCAL_CMD_SETTINGS_DIR=$APP_ROOT/.clientcal-settings &&\
   #
   # run "composer update"
   #
   su - $APP_USER -c "cd $APP_ROOT && composer update" &&\
   #
   # install "clientcal command utility" system-wide 
   #
   ln -s $CLIENTCAL_CMD_SOURCE_PATH $CLIENTCAL_CMD_INSTALL_PATH &&\
   #
   # prepare local system settings directory for "clientcal command utility"
   #
   su - $APP_USER -c "mkdir -p $CLIENTCAL_CMD_SETTINGS_DIR" &&\
   #
   # local system settings for "clientcal command utility" 
   # - disable docker features   
   #
   su - $APP_USER -c "echo 0 > $CLIENTCAL_CMD_SETTINGS_DIR/docker-enabled"

#
# Define volumes
#
VOLUME [ \
   #
   # Allow source code changes to ClientCall 'app' dir
   # - /var/www/clientcal/app
   "$APP_USER_HOME/app", \
   #
   # Allow source code changes to ClientCall 'bin' dir
   # - /var/www/clientcal/bin
   "$APP_ROOT/bin", \
   #
   # Allow source code changes to ClientCall 'lib' dir
   # - /var/www/clientcal/lib
   "$APP_ROOT/lib", \
   #
   # Allow source code changes to ClientCall 'src' dir
   # - /var/www/clientcal/src
   "$APP_ROOT/src", \
   #
   # Allow source code changes to ClientCall 'web' dir
   # - /var/www/clientcal/web
   "$APP_ROOT/web" \
]

#
# Entrypoint
#
COPY ./docker/image/php/docker-entrypoint.sh /var/local/docker-entrypoint.sh
ENTRYPOINT [ "/var/local/docker-entrypoint.sh" ]