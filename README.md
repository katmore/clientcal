# ClientCal
Scheduling, job, crew, and customer management web application for construction companies.
 * Homepage: https://katmore.github.io/clientcal
 * Download: [.zip file](https://github.com/katmore/clientcal/zipball/latest-release), [.tar.gz](https://github.com/katmore/clientcal/tarball/latest-release)

## Description
ClientCal is a PHP+MySQL based project initially released in 2006 to facilitate the management of customer info, job scheduling, job site info, and work crew scheduling for small to medium sized construction operations (SMBs). As a traditional HTML form based application it has proved to be very stable over the years. I have released this project in preparation for improvements to the UI (to make it mobile friendly) and implementing a RESTful API backend.

## Related Resources
 * Github: https://github.com/katmore/clientcal
 * Packagist: https://packagist.org/packages/katmore/clientcal
 * Latest release: https://github.com/katmore/clientcal/releases/latest
 * Documentation: https://github.com/katmore/clientcal/wiki

## Documentation
 * [Installation](https://github.com/katmore/clientcal/wiki/Installation)
 * [Utilities](https://github.com/katmore/clientcal/wiki/Utilities)
 * [Updating](https://github.com/katmore/clientcal/wiki/Updating)
 * [Docker](https://github.com/katmore/clientcal/wiki/Docker)
 
## Quick Start using Docker
Consult the [Docker](https://github.com/katmore/clientcal/wiki/Docker) page of the [ClientCal wiki](https://github.com/katmore/clientcal/wiki) for more information regarding usage with Docker.

 * Prerequisites: a recent version of Docker and Docker-compose
 * Copy, start, and configure ClientCal
    ```sh
    $ git clone https://github.com/katmore/clientcal.git 
    $ cd clientcal/docker/compose/clientcal
    $ docker-compose build
    $ docker-compose start
    $ docker-compose exec php-fpm /var/www/clientcal/bin/web-user.php add
    ```
 * Ready to view in browser at: http://localhost:8080
 
## Quick Start without Docker
Consult the [Installation](https://github.com/katmore/clientcal/wiki/Installation) page of the [ClientCal wiki](https://github.com/katmore/clientcal/wiki) for more detailed instructions.

 * Prerequisites: a web server with PHP 7.2, and a running MySQL server
 * Prepare a database for "clientcal":
   ```sh
   $ echo 'CREATE DATABASE clientcal' | mysql
   $ MYPASS=$(date +%s | sha256sum | base64 | head -c 32 ; echo)
   $ echo "GRANT ALL ON clientcal.* TO 'clientcal'@'localhost' IDENTIFIED BY '$MYPASS'" | mysql
   $ echo "clientcal mysql password: $MYPASS"
   ```
     *be sure to take note of the "clientcal mysql password" for the next step*
     
* Copy and configure ClientCal
  ```sh
  $ git clone https://github.com/katmore/clientcal.git 
  $ cd clientcal
  $ composer update
  $ php bin/config-update.php
  $ php bin/db-update.php
  $ php bin/web-user.php add my_user
  $ CLIENTCAL_WEB_ROOT=$(pwd)/web
  $ echo "clientcal web root: $CLIENTCAL_WEB_ROOT"
  ```
     *be sure to take note of the "clientcal web root" for the next step*
  
 * Edit your HTTP server's "Document Root" to be the **clientcal web root**
 
    --OR--
    
 * Create a symlink to the **clientcal web root** on the exiting "Document Root" 
   ```sh
   $ ln -s $CLIENTCAL_WEB_ROOT /var/www
   ```

## Screenshots
See the [Screenshots](https://katmore.github.io/clientcal/#screenshots) section of the [ClientCal homepage](https://katmore.github.io/clientcal/#screenshots).

## Legal
ClientCal is distributed under the terms and conditions of the MIT license (see [LICENSE](/LICENSE)), or the terms and conditions of the GPLv3 license (see [GPLv3](/GPLv3)).

Copyright (c) 2006-2018, Paul Douglas Bird II.
All rights reserved.
