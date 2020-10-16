The standard library is a set of multiple functions that are accessible from anywhere inside Wolff and can be called like native PHP functions.

The standard library can be disabled, meaning that its functions won't be available in the global namespace. To disable it, set to `false` the `stdlib_on` key in the configuration array of the `system/config.php` file.

## General

### Path

`path([string $path])`

Returns the absolute path of the given relative path. 
Keep in mind that the given path is supposed to be relative to the project root folder.

```php
path('app/controllers');
```

If the wolff project is located at `/var/www/html/wolff`, it will return `/var/www/html/wolff/app/controllers`.

### Relative path

`relativePath([string $path])`

Returns the given absolute path converted to a relative path.
The returned path is relative to the project root folder.

```php
relativePath('/home/usbac/Documents/wolff/app/controllers');
```

If the wolff project is located at `/home/usbac/Documents/wolff`, it will return `app/controllers`.

### Is associative

`isAssoc(array $arr)`

Returns `true` if the given array is associative, `false` otherwise.

_Keep in mind that in the context of the function, an associative array is an array with non-numeric keys._

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

`val(array $arr[, string $key])`

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

### Array remove

`arrayRemove(array &$arr, $val)`

Removes an element from the given array, based on its value. 

It returns `true` if the element has been removed, `false` otherwise. 

```php
$ships = [
    'mauretania', 'lusitania', 'queen_mary'
];

arrayRemove($ships, 'lusitania');
```

After that, the `ships` variable should look like this:

```
(
    [0] => mauretania
    [2] => queen_mary
)
```

_Keep in mind that the array's keys are preserved even for non-assosiative arrays._

### Bytes to string

`bytesToString($size[, $precision])`

Returns the given size (in bytes) as a human-readable string. The default precision is `2`.

```php
bytesToString('540000')
```

That example should return '527KB'.

```php
bytesToString('10000000', 3)
```

That example should return '9.537MB'.

_The human-readable string to return can go from B (byte) to YB (yottabyte)._

### Average

`average(array $arr)`

Returns the average value of the given numbers array.

```php
average([ 2.5, 5.46, 4, 9 ]);
```

That should return `5.24`.

### Echo and die

`echod(...$args)`

Echo a variable and then die (for debugging purposes).

```php
echod('Lorem ipsum dolor sit amet');
```

Multiple parameters can be passed.

```php
echod('Lorem', 'ipsum', 'dolor');
```

### Print

`printr(...$args)`

Prints the given variables in a nice looking way.

```php
$array = ['laravel', 'codeigniter', 'wolff', 'yii'];
printr($array);
```

This function can take any number of parameters.

```php
printr($array, $foo, $foo2...);
```

### Print and die

`printrd(...$args)`

Prints the given variables in a nice looking way and then die.

```php
$array = ['laravel', 'wolff', 'yii'];
printrd($array);
```

This function can take any number of parameters.

```php
printrd($array, $foo, $foo2...);
```

### Var dump and die

`dumpd(...$args)`

Var dump a variable and then die (for debugging purposes).

```php
$str = 'Hello world';
dumpd($str);
```

### Validate CSRF

`validateCsrf()`

Returns `true` if the current request is safe from csrf (cross site request forgery), `false` otherwise.

This simply verifies that the current user is the one who made the request to the application.

```php
if (validateCsrf()) {
    echo 'The incoming form was made by the user, continue :)';
    // Code
} else {
    echo 'You shall not pass';
}
```

This can be combined with the `@csrf` tag available in the template engine.

If you don't want to turn on the standard library just for using this function, you can make your own implementation.

```php
function validateCsrf()
{
    // Get
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return isset($_GET['__token'], $_COOKIE['__token']) && $_GET['__token'] === $_COOKIE['__token'];
    }

    // Post
    return isset($_POST['__token'], $_COOKIE['__token']) && $_POST['__token'] === $_COOKIE['__token'];
}
```

### Is int

`isInt($int)`

Returns `true` if the given parameter complies with an integer, `false` otherwise.

```php
isInt('1');
isInt(1);
```

Both of the calls showed above will return `true`.

### Is float

`isFloat($float)`

Returns `true` if the given parameter complies with a float, `false` otherwise.

```php
isFloat('1.5');
isFloat(1.5);
```

Both of the calls showed above will return `true`.

### Is bool

`isBool($bool)`

Returns `true` if the given parameter complies with an boolean, `false` otherwise.

```php
isBool(true);
isBool('1');
```

Both of the calls showed above will return `true`.

Only the numeric values `1` and `0`, and the strings 'true', 'false', '1' and '0' are counted as boolean.

### Is json

`isJson(string $str)`

Returns `true` if the given string is a json, `false` otherwise.

_Notice: This function modifies the `json_last_error` value._

```php
$json = '{name: "John", age: 21, city: "New York"}';
isJson($json);
```

That will return `true`.

### To array

`toArray($obj)`

Returns the given variable as an associative array.

Useful when it is necessary to turn a multidimensional json or std object into an array.

```php
$json = '{name: "John", age: 21, city: "New York"}';
toArray($json);
```

### Get url

`url([string $url])`

Returns the given string as a local url. Useful for redirections.

Example:

```php
url('home/sayHello');
```

If the project is located at `http://localhost/wolff`, the function will return `http://localhost/wolff/home/sayHello`.

If the project is located at `https://www.getWolff.com`, the function will return `https://www.getWolff.com/home/sayHello`.

### Get client Ip

`getClientIP()`

Returns the current client IP.

```php
getClientIP();
```

In localhost it will return `::1`.

### Local

`local()`

Returns `true` if the current request is running in localhost, `false` otherwise.

```php
local();
```

### Get current page

`getCurrentPage()`

Returns the current url.

```php
getCurrentPage();
```

### Get pure current page

`getPureCurrentPage()`

Returns the current url without parameters.

```php
getPureCurrentPage();
```

If the current url is `example.com/homepage?id=2` it will return `example.com/homepage`.

### Get benchmark time

`getBenchmark()`

Returns the time between the page load start and the current time in seconds as float.

```php
getBenchmark();
```

### Get public

`getPublic([string $path])`

Returns the absolute public path of the given path.

```php
getPublic('favicon.ico');
```

If the project is located at `/var/www/html/wolff` that should return `/var/www/html/wolff/public/favicon.ico`.

### Get config

`config(string $key)`

Returns the specified key value from the `CONFIG` array, or from the environment file if `env_override` is set to `true`.

```php
config('root_dir');
```

The key accepts dot notation.

```php
config('db.password');
```

### Get Wolff version

`wolffVersion()`

Returns the current version of Wolff.

```php
wolffVersion();
//In this case it should return 2.0
```

## Adding functions to the Standard library

You can add your own functions to the standard library this way.

1. Create a php file with the following structure in the `system` folder:

```php
<?php

namespace {
    function example() {
        //Code
    }
}
```

2. Add all the functions you want.

3. Add the following line to your composer.json file inside the autoload > files array

```
"system/yourfilename.php",
```

And you are done, now your functions should be available inside all of your Wolff project.

_Remember to run `composer dump-autoload` for the changes to take effect._
