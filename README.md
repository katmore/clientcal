# ClientCal
Scheduling, job, crew, and customer management web application for construction companies.

[ClientCal Project Homepage](https://github.com/katmore/clientcal)

## Description
ClientCal is a PHP/MySQL based project initially released in 2006 to facilitate the management of customer info, job scheduling, job site info, and work crew scheduling for small to medium sized construction operations (SMBs). As a traditional HTML form based application it has proved to be very stable over the years. I have released this project in preparation for improvements to the UI (to make it mobile friendly) and implementing a RESTful API backend.

## Installation
Completing the installation of ClientCal involves 4 steps (after you have installed it somewhere).

```sh
git clone https://github.com/katmore/clientcal.git 
cd clientcal
```

### Step 1: Composer update
Use composer update to install the necessary dependencies.
```sh
composer update
```

### Step 2: Initialize Database

Use the the command line **database installer script** ([bin/db-install.php](bin/db-install.php)) to install the latest database structure or apply any updates to an existing database.

The db installer can be invoked without any arguments; it will prompt for all the required parameters (such as the host, dbname, user, pass, etc.).
```sh
php bin/db-install.php
```

The `--help` switch will provide details on more advanced usage (such as quiet and non-interactive modes).
```sh
php bin/db-install.php --help
```

The db installer reads the [app/data/mysql/schema.json](app/data/mysql/schema.json) file which specifies the location of SQL dump files and the latest database structure version for the project. The latest SQL update and structure dumps for ClientCal are contained in the [app/data/mysql/schema-sql](app/data/mysql/schema-sql) directory.

### Step 3: Update configuration

Use the command line **config update script** ([bin/config-update.php](bin/config-update.php)) to customize and properly configure the local clientcal installation.

The config update script can be invoked without any arguments; it will prompt for all the necessary configuration values.

```sh
php bin/config-update.php
```

The `--help` switch will provide details on more advanced usage (such as quiet and non-interactive modes).
```sh
php bin/config-update.php --help
```

### Step 4: Create a login user

The command line **user manager script** ([bin/clientcal-user.php](bin/clientcal-user.php)) can be used to manage ClientCal login users.

Usage:
```
php bin/clientcal-user.php <ACTION:add|change|remove> <USERNAME> [<PASSWORD>]
```

To create a new user, use the **add** `<ACTION>`:
```sh
php bin/clientcal-user.php add my_user
```

To change an existing user's password, use the **change** `<ACTION>`:
```sh
php bin/clientcal-user.php change my_user
```

To remove an existing user, use the **remove** `<ACTION>`:
```sh
php bin/clientcal-user.php remove my_user
```

The `--help` and `--usage` switches provide more details regarding usage:
```sh
php bin/clientcal-user.php --help
php bin/clientcal-user.php --usage
```

## Legal
ClientCal is distributed under the terms of the MIT license or the GPLv3 license.

Copyright (c) 2006-2018, Paul Douglas Bird II.
All rights reserved.
