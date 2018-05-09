# ClientCal
Scheduling, job, crew, and customer management web application for construction companies.

[ClientCal Project Homepage](https://github.com/katmore/clientcal)

## Description
ClientCal is a PHP/MySQL based project initially released in 2006 to facilitate the management of customer info, job scheduling, job site info, and work crew scheduling for small to medium sized construction operations (SMBs). As a traditional HTML form based application it has proved to be very stable over the years. I have released this project in preparation for improvements to the UI (to make it mobile friendly) and implementing a RESTful API backend.

## Installation
Completing the installation of ClientCal involves 5 steps (after you have installed it somewhere).

```sh
git clone https://github.com/katmore/clientcal.git 
cd clientcal
```

### Installation - Step 1
**Composer update**

Use `composer update` to install the necessary dependencies.
```sh
composer update
```

### Installation - Step 2
**MySQL Preparation**

A MySQL (or MariaDB) database must be provisioned along with a MySQL user with corresponding permissions by external means, such as with the mysql console.

```txt
mysql> CREATE DATABASE clientcal;
mysql> GRANT ALL ON clientcal.* TO 'clientcal'@'localhost' IDENTIFIED BY ...
```

### Installation - Step 3
**Initialize Database**

Use the the command-line [**database update utility**](#database-update-utility) to install the latest database structure.

```sh
php bin/db-update.php
```

### Installation - Step 4
**Initialize configuration**

Use the command-line [**config update utility**](#configuration-update-utility) to customize and properly configure the local clientcal installation.

```sh
php bin/config-update.php
```

### Installation - Step 5
**Create a web user**

Use the command-line [**user manager utility**](#user-manager-utility) to create at least one web user that can login to the ClientCal web application.

```sh
php bin/clientcal-user.php add my_user
```

## Update Workflow
Active deployments of ClientCal can be safely upgraded in-place using a 3 step workflow.

The ClientCal source itself can be updated using the same means as when it was copied to a local system initially.

```sh
cd clientcal
git update
```

### Update Workflow - Step 1
Use `composer update` to upgrade and install any new dependencies.
```sh
composer update
```

### Update Workflow - Step 2
Use the [**config update utility**](#configuration-update-utility) command-line script to safely upgrade the existing ClientCal configuration.

```sh
php bin/config-update.php
```

### Update Workflow - Step 3
Use the [**database update utility**](#database-update-utility) command-line script to safely apply database schema updates (database migrations).

```sh
php bin/db-update.php
```

## Utilities
The ClientCal project includes command-line utility scripts to facilitate installation and ongoing operation.
 * [user manager utility](#user-manager-utility)
 * [database export utility](#database-export-utility)
 * [database update utility](database-update-utility)
 * [configuration update utility](#configuration-update-utility)

### user manager utility
 * Location: [bin/clientcal-user.php](bin/clientcal-user.php)

The **user manager utility** manages ClientCal web users. Web users can be created, removed, and modified.

The `--help` and `--usage` switches provides details regarding advanced usage (such as quiet mode and avoiding prompts):
```sh
php bin/clientcal-user.php --help
php bin/clientcal-user.php --usage
```

The **add** `<ACTION>` creates a new user:
```sh
php bin/clientcal-user.php add my_user
```

The **set-password** `<ACTION>` modifies an existing user's password:
```sh
php bin/clientcal-user.php set-password my_user
```

The **remove** `<ACTION>` removes an existing user:
```sh
php bin/clientcal-user.php remove my_user
```

### database export utility
 * Location: [bin/db-export.php](bin/db-export.php)
 
The **database export utility** facilitates the creation of database backups of the ClientCal database using the local installation configuration.

The `--help` and `--usage` switches provides details regarding advanced usage (such as quiet mode or specifying the output file basename and full path):
```sh
php bin/clientcal-user.php --help
php bin/clientcal-user.php --usage
```

### database update utility
 * Location: [bin/config-update.php](bin/config-update.php)

The **database update utility** can be invoked without any arguments; it will prompt for all the required parameters (such as the host, dbname, user, pass, etc.). It reads the [app/data/db-schema.json](app/data/db-schema.json) file which specifies the latest database structure version and relative locations of the corresponding SQL resources. The latest SQL update and structure dumps for ClientCal are contained in the [app/data/schema-sql](app/data/schema-sql) directory. When a database is empty (i.e. contains no tables), the database is created from scratch using the SQL dump file of the latest database revision. For existing databases, migrations are perfomed using SQL update files as specified by a database revsions corresponding `db-version.json` file. The current applied schema version is stored in the table `schema_version` of the same database.

The `--help` and `--usage` switches provide details regarding advanced usage (such as quiet mode and avoiding prompts).
```sh
php bin/db-update.php --help
php bin/db-update.php --usage
```

### configuration update utility
 * Location: [bin/db-update.php](bin/db-update.php)

```sh
php bin/config-update.php
```
The **configuration update utility** creates and updates configuration file values in the ClientCal config path (/app/config/clientcal) by reading values from `*-sample.php` files. The utility provides a prompt to input each configuration value; along with a default value. The utility resolves the "default value" by using the existing configuration value; if no configuration value yet exists, the "default value" is obtained from the `-sample.php` file. If the utility is started with the `--non-interactive` option, the default values are automatically used. 

The `--help` and `--usage` switches provide details regarding advanced usage (such as quiet mode and avoiding prompts).
```sh
php bin/config-update.php --help
php bin/config-update.php --usage
```

## Docker environment
A docker environment is provided using docker-compose.
 * [docker-compose.yml](/docker/compose/clientcal/docker-compose.yml)

### Docker environment requirements
 - docker version 18.03 or newer
 - docker-compose version 1.19 or newer

### Docker-compose for development and testing
The default configuration is suitable for local development and testing.

**basic usage**
```
cd docker/compose/clientcal
docker-compose build
docker-compose up
```

**what occurs during 'up' or 'start'**
 * non-ephemerial MySQL data is mounted on the host system
 * the database is initialized if starting the first time using the latest schema dump according to [db-schema.json](/app/data/schema-sql/db-schema.json)
 * php-fpm starts, and is available to nginx
 * nginx starts, and the ClientCal application is available on the local system
   * Default Entrypoint URL: http://localhost:8080
 * PHP errors are logged to the terminal as they occur (as long as the 'php-fpm' container is attached to your terminal, i.e. `docker-compose up`)
 * nginx errors are logged to the terminal as they occur (as long as the 'nginx' container is attached to your terminal, i.e. `docker-compose up`)

### Docker-compose for public deployment
The docker-compose configuration included with this project IS NOT suitable for public deployment of ClientCal as provided. It could be made suitable to deploy publicly only if modifications are made to provide provide SSL. 

Examples of the easier ways this could be done are as follows:
 * modifying the provided nginx configuration (such as with a volume mount) to use SSL
 * deploying a separate public facing HTTP server with SSL that is configured to be a reverse proxy to the clientcal docker network port 8080
 * deploying a separate public facing HTTP server with SSL that is configured to use CGI over the clientcal docker network port 9000

## Docker images
 * [php](/docker/image/php/Dockerfile)
 * [mariadb](/docker/image/mariadb/Dockerfile)

### PHP docker image
The "php" docker image contains all dependencies for running the ClientCal web application.
It is suitable for stand-alone use (i.e. `docker run ...`) or in conjunction with docker-compose (such as configured in the "docker environment" included in this project). It uses [alpine linux](https://hub.docker.com/_/alpine/) and the [PHP Repositories for Alpine - by CODECASTS](https://github.com/codecasts/php-alpine) for php related packages.

### MariaDB docker image
The "mariadb" docker image provides a MariaDB service suitable for use witht he "docker-compose environment" included in this project. It uses the [official mariadb image](https://hub.docker.com/_/mariadb/) with some user permissions tweaks to better facilitate executing seed scripts and saving non-ephemerial data to the host.


## Legal
ClientCal is distributed under the terms of the MIT license or the GPLv3 license.

Copyright (c) 2006-2018, Paul Douglas Bird II.
All rights reserved.
