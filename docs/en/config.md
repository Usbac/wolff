Configuration is a vital part of any application, the Wolff configuration can be defined in two ways, a `system/config.php` file and an environment file.

## Config file

The config file has the following definitions/keys inside an array that is being returned by the file itself. 

The file looks like this:

```php
return [
    'db' => [
        'dsn'      => 'mysql:host=localhost;dbname=testdb', //Database dsn string
        'username' => 'wolf', //Database username
        'password' => '12345', //Database password
    ],
    'env' => [
        'file'     => 'system/.env.example', //Path of the .env file (by default it's .env)
        'override' => true, //If true the env variables will override the config data
    ],
    'language'       => 'english', //Default language
    'development_on' => true, //Development mode status (in development mode all errors will be displayed)
    'template_on'    => true, //Template engine status (enabled or not)
    'cache_on'       => true, //Cache system status (enabled or not)
    'stdlib_on'      => true, //Standard library status (enabled or not)
    'maintenance_on' => false, //Maintenance mode status, If true the page will be in maintenance
];
```

## Environment

The data of the environment file (defined in the `system/config.php` file) can be accessed through:

* The `getenv` function.
* `$_ENV` superglobal array.
* The `Wolff\Core\Config` array. *
* The `config` function of the standard library. *

\* Only accesible if the `env.override` is set to `true` in the `system/config.php` file.

_Keep in mind that the environment key must be written in lowercase for the `config` function of the standard library and the `get` method of the `Wolff\Core\Config` class. Meaning that `$_ENV['LANGUAGE']` is equivalent to `config('language')`._

## Config class

The `Wolff\Core\Config` class has the `get` method which can be used to get the current configuration.

`get([string $key]): mixed`

```php
Wolff\Core\Config::get('language');
```

It returns the configuration value of the given key (or environment value if the `env.override` is set to `true`).

The key accepts dot notation.

```php
Wolff\Core\Config::get('db.username'); 
// Equivalent to Wolff\Core\Config::get('db')['username']
```

### Example

system/config.php:

```php
return [
    'language' => 'english',

    'env' => [
        'file'     => 'system/.env',
        'override' => false,
    ],
];
```

system/.env:

```
LANGUAGE='spanish'
```

In this case `Wolff\Core\Config::get('language')` and `config('language')` will return `english`. If you set `env.override` to `true`, both will return `spanish` instead.
