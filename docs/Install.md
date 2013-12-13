# Installing Ushahidi 3.x

### System Requirements

To install the platform on your computer/server, the target system must meet
the following requirements:

  * PHP version 5.3.0 or greater
  * Database Server
    * MySQL version 5.5 or greater
    * PostgreSQL support is coming
  * An HTTP Server. Ushahidi is known to work with the following web servers:
    * Apache 2.2+
    * nginx
  * Unicode support in the operating system

### [](https://github.com/ushahidi/Lamu/edit/master/README.md#getting-the-
code)Getting the code

You can get the code by cloning the github repo.

You need to use `--recursive` to initialize and clone all the submodules. If
you've already cloned without submodules you can already initialize (or
update) them but running:

### Installing

  1. Get the code by cloning the [git repo](https://github.com/ushahidi/Lamu)

(-recursive is needed to make sure submodules are cloned too)

  2. Create a database
  3. Copy _application/config/database.php_ to _application/config/environments/development/database.php_
  4. Edit _application/config/environments/development/database.php_ and set database, username and password params

php

  5. Install the database schema using migrations

  6. Copy _application/config/init.php_ to _application/config/environments/development/init.php_
  7. Edit _application/config/environments/development/init.php_ and change base_url to point the the httpdocs directory in your deployment
  8. Copy _httpdocs/template.htaccess_ to _httpdocs/.htaccess_
  9. Edit _httpdocs/.htaccess_ and change the RewriteBase value to match your deployment url
  10. Create directories _application/cache_ and _application/logs_ and make sure they're writeable by your webserver

### Configuration

Base config files are in _application/config/._

  
You can add per-environment config overrides in
_application/config/environments/._ The environment is switched based on the
_KOHANA_ENV_ environment variable.

  
Routes are configured in _application/routes/default.php_. Additional routes
can be added in per-environment routing files ie.
_application/routes/development.php_.


