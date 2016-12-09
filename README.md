# ClientCal
Scheduling, job, crew, and customer management web application for construction companies.

[ClientCal Project Homepage](https://github.com/katmore/clientcal)

## Description
ClientCal is a PHP/MySQL based project initially released in 2006 to facilitate the management of customer info, job scheduling, job site info, and work crew scheduling for small to medium sized construction operations (SMBs). As a traditional HTML form based application it has proved to be very stable over the years. I have released this project in preparation for improvements to the UI (to make it mobile friendly) and implementing a RESTful API backend.

## Installation
The simplest way prepare ClientCal for installation is to copy this project somewhere and run Composer:
```bash
git clone https://github.com/katmore/clientcal.git 
cd clientcal
composer update
```

Install the latest database structure or apply updates by using the the command line [db-install.php](bin/db-install.php) installer script. It applies the database structure as defined by [app/data/mysql/schema.json](app/data/mysql/schema.json) which refers to the SQL dump files contained in the [schema-sql](app/data/mysql/schema-sql) directory. 

The db installer can be invoked without any arguments; it will prompt for all the required parameters (such as the remote URL, local repo path, webhook secret, etc.).
```bash
php bin/db-install.php
```

The `--help` switch will provide details on more advanced usage (such as quiet and non-interactive modes).
```bash
php bin/db-install.php --help
```

##Legal
ClientCal is distributed under the terms of the MIT license or the GPLv3 license.

Copyright (c) 2006-2017, Paul Douglas Bird II.
All rights reserved.
