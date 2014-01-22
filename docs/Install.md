# Installing Ushahidi 3.x

### System Requirements

To install the platform on your computer/server, the target system must meet
the following requirements:

* PHP version 5.3.0 or greater
* Database Server
    - MySQL version 5.5 or greater
    - PostgreSQL support is coming
* An HTTP Server. Ushahidi is known to work with the following web servers:
    - Apache 2.2+
    - nginx
* Unicode support in the operating system


### Getting the code

You can get the code by cloning the github repo.

```
git clone --recursive https://github.com/ushahidi/Lamu
```

You need to use ```--recursive``` to initialize and clone all the submodules.
If you've already cloned without submodules you can already initialize (or
update) them but running:

```
git submodule update --init --recursive
```

### Installing
1. Create a database
2. Copy ```application/config/database.php``` to ```application/config/environments/development/database.php```
3. Edit ```application/config/environments/development/database.php``` and set
   database, username and password params

  ```
  return array
  (
    'default' => array
    (
      'type'       => 'MySQLi',
      'connection' => array(
        'hostname'   => 'localhost',
        'database'   => 'lamu',
        'username'   => 'lamu',
        'password'   => 'lamu',
        'persistent' => FALSE,
      ),
      'table_prefix' => '',
      'charset'      => 'utf8',
      'caching'      => TRUE,
      'profiling'    => TRUE,
    )
  );
  ```

4. Copy ```application/config/init.php``` to ```application/config/environments/development/init.php```

   > **A note on urls, docroots and base_url**
   >
   > The repository is set up so that ```httpdocs``` is expected to be the doc
   > root. If the docroot on your development server is /var/www and you put
   > the code into /var/www/lamu then the base_url for your deployment is
   > going to be http://localhost/lamu/httpdocs/
   >
   > If you're installing a live deployment you should set up a virtual host
   > and make the ```DocumentRoot``` point directly to ```httpdocs```.
   >
   > If you can't use a vhost you can copy just the httpdocs directory into
   > your docroot, rename it as needed. Then update the paths for application,
   > modules and system in index.php.

5. Edit ```application/config/environments/development/init.php``` and change
   base_url to point the the httpdocs directory in your deployment
6. Copy ```httpdocs/template.htaccess``` to ```httpdocs/.htaccess```
7. Edit ```httpdocs/.htaccess``` and change the RewriteBase value to match
   your deployment url
8. Create directories ```application/cache```, ```application/media/uploads```
   and ```application/logs``` and make sure they're writeable by your webserver
    ```
    mkdir application/cache application/logs application/media/uploads
    chown www-data application/cache application/logs application/media/uploads
    ```
9. Install the database schema using migrations

  ```
  ./minion --task=migrations:run --up
  ```

### Logging in the first time

The default install creates a user 'demo' with password 'testing'. This user
has admin privileges. Once logged in this user can create further user
accounts or give others admin permissions too.

### Configuration

Base config files are in ```application/config/```.

You can add per-environment config overrides in
```application/config/environments/```. The environment is switched based on
the ```KOHANA_ENV``` environment variable.

Routes are configured in ```application/routes/default.php```. Additional
routes can be added in per-environment routing files ie.
```application/routes/development.php```.


