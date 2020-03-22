Beside the custom libraries you can use and create in Wolff, there’s the standard library which is used by Wolff itself, but you can use it too.

These functions are accessible from anywhere inside Wolff just like the native PHP functions.

## General

### Is associative

Returns true if the given array is associative, false otherwise.

Take in mind that in the context of the function, an associative array is an array with non-numeric keys.

```php
$arr = [
    'name' => 'Margaret',
    'age'  => 63
];

isAssoc($arr);
```

That should return `true`.

```php
isAssoc([ 'Margaret', 'Thomas', 'Edward' ]);
```

That should return `false`.

### Value

Returns the specified key from the given array. If the given key doesn't exists it will simply return `null`.

```php
$arr = [
    'name' => 'Margaret',
    'age'  => 63
];

val($arr, 'name');
```

That should return the string `Margaret`.

The key accepts dot notation:

```php
val($arr, 'user.name');
```

That should be the equivalent to `$arr['user']['name']`.

### Average

Returns the average value of the given numbers array.

```php
average([ 2.5, 5.46, 4, 9 ]);
```

That should return `5.24`.

### Echo and die

Echo a variable and then die (for debugging purposes).

```php
echod('Lorem ipsum dolor sit amet');
```

### Print array

Print an array in a nice looking way

```php
$array = ['laravel', 'codeigniter', 'wolff', 'yii'];
printr($array);
```

This function can take any number of parameters.

```php
printr($array, $array2, $array3...);
```

### Print array and die

Print an array in a nice looking way and then die

```php
$array = ['laravel', 'codeigniter', 'wolff', 'yii'];
printrd($array);
```

This function can take any number of parameters.

```php
printrd($array, $array2, $array3...);
```

### Var dump and die

Var dump a variable and then die (for debugging purposes).

```php
$str = 'Hello world';
dumpd($str);
```

### Var dump all

Var dump all the current variables.

```php
dumpAll();
```

### Is int

Returns true if the given parameter complies with an integer.

```php
isInt('1');
isInt(1);
```

Both of the calls showed above will return true.

### Is float

Returns true if the given parameter complies with an integer.

```php
isFloat('1.5');
isFloat(1.5);
```

Both of the calls showed above will return true.

### Is bool

Returns true if the given parameter complies with an boolean.

```php
isBool(true);
isBool('1');
```

Both of the calls showed above will return true.

Only the numeric values 1 and 0, and the strings 'true', 'false', '1' and '0' are counted as boolean.

### Is json

Returns true if the given string is a json.

```php
$json = '{name: "John", age: 21, city: "New York"}';
isJson($json);
```

That will return true.

### To array

Returns the given variable as a associative array.

Useful when it is necessary to turn a multidimensional json or std object to an array.

```php
$json = '{name: "John", age: 21, city: "New York"}';
toArray($json);
```

### Array to csv

Convert an array result into a csv file and then downloads it.

```php
$array = [
    0 => [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ],
    1 => [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com'
    ],
];

//The first parameter is the array, the second is the desired file name (without extension).

arrayToCsv($array, 'filename');
```

### Get url

Returns the given string as a local url. Useful for redirections.

Example:

```php
url('home/sayHello');
```

If the project is located at `http://localhost/wolff`, the function will return `http://localhost/wolff/home/sayHello`.

If the project is located at `https://www.getWolff.com`, the function will return `https://www.getWolff.com/home/sayHello`.

### Get client Ip

Returns the current client IP.

```php
getClientIP();
//In localhost it will return ::1
```

### Get user agent

Returns the HTTP User Agent information (equivalent to `$_SERVER['HTTP_USER_AGENT']`).

```php
getUserAgent();
```

### Get server root

Returns the server root path (equivalent to `$_SERVER['DOCUMENT_ROOT']`).

```php
getServerRoot();
```

### Local

Returns true if the current request is running in localhost.

```php
local();
```

### In CLI

Returns true if the current code is running in a Command Line Interface, false otherwise.

```php
inCli();
```

### Get current page
Returns the current url relative to the project root directory.

```php
getCurrentPage();
//If the current url is localhost/wolff/homepage it will return 'homepage'.
```

### Get pure current page
Returns the complete current url without parameters.

```php
getPureCurrentPage();
//If the current url is http://example.com/homepage?id=2 it will return 'http://example.com/homepage'.
```

### Get benchmark time
Returns the time between the page load start and the current time in seconds as float.

```php
getBenchmark();
```

## Config

You can get the configuration constants through the following functions.

_Keep in mind that the paths returned by the `config.php` functions are relative to the server root, except for the `getPublic` and `getProjectDir` functions._

_The functions related to directories can take a string as a parameter which will be concatenated to the directory returned. Example:_

```php
getPublic('home.png');
//In this case it will return 'localhost/wolff/public/home.png'
```

### Get config value

Returns the specified key value from the `CONFIG` array. If the given key doesn't exists it will simply return `null`.

```php
config('root_dir');
```

That is the equivalent to `CONFIG['root_dir']`.

The key accepts dot notation:

```php
config('db.password');
```

That is the equivalent to `CONFIG['db']['password']`.

### Get server

Returns the current server.

```php
getServer();
```

### Get database

Returns the current database name.

```php
getDB();
```

### Get database management system

Returns the current database management system.

```php
getDBMS();
```

### Get database user

Returns the current database username.

```php
getDbUser();
```

### Get database password

Returns the current database username password.

```php
getDbPass();
```

### Get language

Returns the current language being used by Wolff.

```php
getLanguage();
```

### Get directory

Returns the project root path.

```php
getDir();
```

### Get project directory

Returns the project root path relative to the server root.

```php
getProjectDir();
```

### Get system directory

Returns the system folder path.

```php
getSystemDir();
```

### Get app directory

Returns the app folder path.

```php
getAppDir();
```

### Get public path

Returns the public folder path.

```php
getPublic();
```

### Get cache directory

Returns the cache folder path.

```php
getCacheDir();
```

### Get page title

Returns the current page title.

```php
getPageTitle();
```

### Get main page

Returns the current main page.

```php
getMainPage();
```

### Get Wolff version

Returns the current version of Wolff.

```php
wolffVersion();
//In this case it should return 2.0
```

# Adding functions to the Standard library

You can add your own functions to the standard library this way.

1. Create a php file with the following structure in the `system/definitions` folder:

```php
<?php

namespace {
    function example() {
        //Code
    }
}
```

2. Add all the functions you want.

3. After that add the following line to your composer.json file inside the autoload > files array

```
"system/definitions/yourfilename.php",
```

4. Remember to run `composer dump-autoload` for the changes to take effect.

And you are done, now your functions are available inside all your Wolff project.
