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

### Step 1: Composer update
Use `composer update` to install the necessary dependencies.
```sh
composer update
```

### Step 2: MySQL Preparation
A MySQL (or MariaDB) database must be provisioned along with a MySQL user with corresponding permissions by external means, such as with the mysql console.

```txt
mysql> CREATE DATABASE clientcal;
mysql> GRANT ALL ON clientcal.* TO 'clientcal'@'localhost' IDENTIFIED BY ...
```

### Step 3: Initialize Database

Use the the command line **database update utility** ([bin/db-update.php](bin/db-update.php)) to install the latest database structure or apply any updates to an existing database.

The db installer can be invoked without any arguments; it will prompt for all the required parameters (such as the host, dbname, user, pass, etc.).
```sh
php bin/db-update.php
```

The `--help` switch will provide details on more advanced usage (such as quiet and non-interactive modes).
```sh
php bin/db-update.php --help
```

The db installer reads the [app/data/mysql/schema.json](app/data/mysql/schema.json) file which specifies the location of SQL dump files and the latest database structure version for the project. The latest SQL update and structure dumps for ClientCal are contained in the [app/data/mysql/schema-sql](app/data/mysql/schema-sql) directory.


### Step 4: Initialize configuration

Use the command line **config update utility** ([bin/config-update.php](bin/config-update.php)) to customize and properly configure the local clientcal installation.

The config update utility can be invoked without any arguments; it will prompt for all the necessary configuration values.

```sh
php bin/config-update.php
```

The `--help` switch will provide details on more advanced usage (such as quiet and non-interactive modes).
```sh
php bin/config-update.php --help
```

### Step 5: Create a login user
The command line **user manager utility** ([bin/clientcal-user.php](bin/clientcal-user.php)) can be used to manage ClientCal login users. At least one login user must be created to operate ClientCal.

Usage:
```
php bin/clientcal-user.php <ACTION:add|set-password|remove> <USERNAME> [<PASSWORD>]
```

The **add** `<ACTION>` creates a new user:
```sh
php bin/clientcal-user.php add my_user
```

The **set-password** `<ACTION>` modifies an existing user's password:
```sh
php bin/clientcal-user.php change my_user
```

The **remove** `<ACTION>` removes an existing user:
```sh
php bin/clientcal-user.php remove my_user
```

The `--help` and `--usage` switches will provide details on more advanced usage (such as quiet mode and avoiding password prompts):
```sh
php bin/clientcal-user.php --help
php bin/clientcal-user.php --usage
```

## Updates
Active deployments of ClientCal can be safely upgraded in-place using a 3 step workflow.

The ClientCal source itself can be updated using the same means as when it was copied to a local system initially.

```sh
cd clientcal
git update
```

### Step 1
Use `composer update` to upgrade and install any new dependencies.
```sh
composer update
```

### Step 2
Use the **config update utility** ([bin/config-update.php](bin/config-update.php)) command-line script to safely upgrade the existing ClientCal configuration.

```sh
php bin/config-update.php
```

### Step 3
Use the **database update utility** ([bin/db-update.php](bin/db-update.php)) command-line script to safely apply database schema updates (database migrations).

```sh
php bin/db-update.php
```

## Legal
ClientCal is distributed under the terms of the MIT license or the GPLv3 license.

Copyright (c) 2006-2018, Paul Douglas Bird II.
All rights reserved.
