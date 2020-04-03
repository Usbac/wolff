Configuration is a vital part of any application, the Wolff configuration can be defined in two ways, a `system/config.php` file and an environment file.

## Config file

The config file has the following definitions/keys inside an array named `CONFIG`:

_Keep in mind that these paths are relative to the project root folder._

### Server

* **dbms**: the database driver, you can choose between `mysql`, `pgsql` and `sqlite`.

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

_It's recommended not to modify these constants._

### Environment

* **env_file**: The path of the .env file, by default it's `.env`.

* **env_override**: If true the environment variables will override the config data in the `Wolff\Core\Config` class and the `config` function helper. (The environment keys are converted to lowercase in the override proccess).

### General

* **title**: the page meta title.

* **language**: the site's main language.

### Others

* **log_on**: the log status, true for enabling the use of the log system, false for disabling it.

* **development_on**: the development status, true if the project is in an development environment, false otherwise (in a development environment all the errors will be displayed).

* **template_on**: the template system status, true for enabling the template in the views, false for disable it.

* **cache_on**: the cache status, true for enabling the use of cache, false for disable it.

* **stdlib_on**: the standard library status, true for enabling its functions in the global namespace, false for disable them.

* **maintenance_on**: the maintenance mode status, true for putting the page in maintenance, false for not.

## Environment

The data of the environment file (defined in the `system/config.php` file) can be accessed through:

* The `getenv` function.
* `$_ENV` superglobal array.
* The `Wolff\Core\Config` array (only if the `env_override` is set to true in the `system/config.php` file).
* The `config` function of the standard library.

## Config class

The `Wolff\Core\Config` class has the `get` method which can be used to get the current configuration.

`get([string $key])`

```php
Wolff\Core\Config::get('title');
```

It returns the config or environment value of the given key (depending if the `env_override` is set to true or not).

### Example

system/config.php:

```php
define('CONFIG', [
    'language' => 'english',

    'env_file'     => 'system/.env',
    'env_override' => false,
]);
```

system/.env:

```
LANGUAGE='spanish'
```

In this case `Wolff\Core\Config::get('language')` and `config('language')` will return `english`. If you set `env_override` to `true`, both will return `spanish` instead.
