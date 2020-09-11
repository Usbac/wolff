Configuration is a vital part of any application, the Wolff configuration can be defined in two ways, a `system/config.php` file and an environment file.

## Config file

The config file has the following definitions/keys inside an array that is being returned by the file itself.

* **db**:

    * **dbms**: the database driver, you can choose between `mysql`, `pgsql` and `sqlite`.

    * **server**: the database host name (usually it’s refered to as localhost).

    * **name**: the database name.

    * **port**: the connection port (this key is optional).

    * **username**: the database username.

    * **password**: the database username password.

* **env_file**: The path of the .env file, by default it's `.env`.

* **env_override**: If `true` the environment variables will override the config data in the `Wolff\Core\Config` class and the `config` function helper. (The environment keys are converted to lowercase in the override proccess).

* **language**: the site's main language.

* **log_on**: the log status, `true` for enabling the use of the log system, `false` for disabling it.

* **development_on**: the development status, `true` if the project is in an development environment, `false` otherwise (in a development environment all the errors will be displayed).

* **template_on**: the template system status, `true` for enabling the template in the views, `false` for disable it.

* **cache_on**: the cache status, `true` for enabling the use of cache, `false` for disable it.

* **stdlib_on**: the standard library status, `true` for enabling its functions in the global namespace, `false` for disable them.

* **maintenance_on**: the maintenance mode status, `true` for putting the page in maintenance,` false` for not.

## Environment

The data of the environment file (defined in the `system/config.php` file) can be accessed through:

* The `getenv` function.
* `$_ENV` superglobal array.
* The `Wolff\Core\Config` array. *
* The `config` function of the standard library. *

\* Only accesible if the `env_override` is set to `true` in the `system/config.php` file.

_Keep in mind that the environment key must be written in lowercase for the `config` function of the standard library and the `get` method of the `Wolff\Core\Config` class. Meaning that `$_ENV['LANGUAGE']` is equivalent to `config('language')`._

## Config class

The `Wolff\Core\Config` class has the `get` method which can be used to get the current configuration.

`get([string $key])`

```php
Wolff\Core\Config::get('title');
```

It returns the config or environment value of the given key (depending if the `env_override` is set to `true` or not).

### Example

system/config.php:

```php
return [
    'language' => 'english',

    'env_file'     => 'system/.env',
    'env_override' => false,
];
```

system/.env:

```
LANGUAGE='spanish'
```

In this case `Wolff\Core\Config::get('language')` and `config('language')` will return `english`. If you set `env_override` to `true`, both will return `spanish` instead.
