# ClientCal
Scheduling, job, crew, and customer management web application for construction companies.
 * Homepage: https://katmore.github.io/clientcal
 * Download: [.zip file](https://github.com/katmore/clientcal/zipball/latest-release), [.tar.gz](https://github.com/katmore/clientcal/tarball/latest-release)

## Description
ClientCal is a PHP+MySQL based project initially released in 2006 to facilitate the management of customer info, job scheduling, job site info, and work crew scheduling for small to medium sized construction operations (SMBs). As a traditional HTML form based application it has proved to be very stable over the years. I have released this project in preparation for improvements to the UI (to make it mobile friendly) and implementing a RESTful API backend.

## Documentation
 * [Installation](https://github.com/katmore/clientcal/wiki/Installation)
 * [Utilities](https://github.com/katmore/clientcal/wiki/Utilities)
 * [Updating](https://github.com/katmore/clientcal/wiki/Updating)
 * [Docker](https://github.com/katmore/clientcal/wiki/Docker)
 
## Quick Start using Docker
Consult the [Docker](https://github.com/katmore/clientcal/wiki/Docker) page of the [ClientCal wiki](https://github.com/katmore/clientcal/wiki) for more information regarding usage with Docker.

 * Prerequisites: 
   * a copy of the ClientCal source (i.e. `git clone https://github.com/katmore/clientcal.git`)
   * a recent version of [*docker*](https://www.docker.com/community-edition)
   * a recent version of [*docker-compose*](https://docs.docker.com/compose/)
 * Copy, start, and configure ClientCal
    ```sh
    $ cd docker/compose/clientcal
    $ docker-compose build
    $ docker-compose start
    $ docker-compose exec php-fpm /var/www/clientcal/bin/web-user.php add
    ```
 * That's it! Ready to view in browser at: http://localhost:8080
 
## Quick Start without Docker
Consult the [Installation](https://github.com/katmore/clientcal/wiki/Installation) page of the [ClientCal wiki](https://github.com/katmore/clientcal/wiki) for more detailed instructions.

 * Prerequisites: 
   * a copy of the ClientCal source (i.e. `git clone https://github.com/katmore/clientcal.git`)
   * PHP 7.2
   * a recent version of [*composer*](https://getcomposer.org/)
   * a web server provisioned as follows:
     * a PHP file handler (i.e. mod_php or php-fpm)
     * a "Document Root" pointing to the ClientCal web root ([clientcal/web](/web))
   * a recent version of MySQL provisioned as follows:
     * an empty MySQL database for "clientcal" (i.e. `CREATE DATABASE "clientcal"`)
     * a MySQL user/pass with access to the database for "clientcal" (i.e. `GRANT ALL ON clientcal.* TO ...`)
 * Copy and configure ClientCal
   ```sh
   $ git clone https://github.com/katmore/clientcal.git 
   $ cd clientcal
   $ composer update
   $ php bin/config-update.php
   $ php bin/db-update.php
   $ php bin/web-user.php add my_user
   ```
 * All done! Ready to view in a browser using your web server's URL.

## Screenshots
See the [Screenshots](https://katmore.github.io/clientcal/#screenshots) section of the [ClientCal homepage](https://katmore.github.io/clientcal/#screenshots).

## Related Resources
 * Github: https://github.com/katmore/clientcal
 * Packagist: https://packagist.org/packages/katmore/clientcal
 * Latest release: https://github.com/katmore/clientcal/releases/latest
 * Documentation: https://github.com/katmore/clientcal/wiki
 * Support/Bugs: https://github.com/katmore/clientcal/issues
 
## Legal
ClientCal is distributed under the terms and conditions of the MIT license (see [LICENSE](/LICENSE)), or the terms and conditions of the GPLv3 license (see [GPLv3](/GPLv3)).

Copyright (c) 2006-2018, Paul Douglas Bird II.
All rights reserved.
