Wolff uses the constants defined in the `system/config.php` file.

The config file has the following definitions/keys inside an array named `CONFIG`:

### Server

* **dbms**: the database management system, you can choose between `mysql`, `pgsql` and `sqlite`.

* **server**: the database host name (usually it’s refered to as localhost).

* **db**: the database name.

* **db_username**: the database username.

* **db_password**: the database username password.

### Directories

* **root_dir**: the directory of the Wolff project.

* **system_dir**: the directory of the system folder.

* **app_dir**: the directory of the app folder.

* **cache_dir**: the directory of the cache folder.

* **public_dir**: the directory of the public folder.

_Keep in mind that these paths are relative to the server root._

_It's recommended not to modify these constants._

### General

* **title**: the page meta title.

* **main_page**: the site's home page.

* **language**: the site's main language, you can create a new language later and change it if you want.

### Others

* **db_on**: the database status, true for enabling the use of the database system and run its initialization, false for disabling it.

* **log_on**: the log status, true for enabling the use of the log system, false for disabling it.

* **development_on**: the development status, true if the project is in an development environment, false otherwise (in a development environment all the errors will be displayed).

* **middlewares_on**: the middleware status, true for enable them, false for disable them.

* **template_on**: the template system status, true for enable the template in the views, false for disable it.

* **cache_on**: the cache status, true for enable the use of cache, false for disable it.

* **maintenance_on**: the maintenance mode status, true for enable the maintenance, false for disable it.
