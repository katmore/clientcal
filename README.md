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
**Create a login user**

At least one login user must exist to operate ClientCal; use the command-line [**user manager utility**](#user-manager-utility) to create one.

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

The **user manager utility** manages ClientCal login users. Users can be created, removed, and modified.

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

## Legal
ClientCal is distributed under the terms of the MIT license or the GPLv3 license.

Copyright (c) 2006-2018, Paul Douglas Bird II.
All rights reserved.
