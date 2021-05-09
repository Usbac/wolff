`Wolff\Core\Controller`

The controllers are a great way to keep your code organized instead of defining everything in the `system/web.php` file.

## Usage

Let's create a controller, it must be in the `Controller` namespace.

Any public method that is supposed to be accesible through a route, can take two parameters which are the request and response objects (instance of `Wolff\Core\Http\Request` and `Wolff\Core\Http\Response`).

app/controllers/Home.php:

```php
namespace Controller;

class Home
{
    public function index($req, $res)
    {
        $res->write('hello world');
    }

    public function sayHi($req, $res)
    {
        $res->write('hi');
    }
}
```

Now if you want to call the controller's method when accessing some routes, you can add the following code to your web file.

system/web.php

```php
Route::get('/', [ Controller\Home::class, 'index' ]);

Route::get('sayHi', [ Controller\Home::class, 'sayHi' ]);
```

## General methods

The `Wolff\Core\Controller` class offers some useful static methods you can use.

### Get controller

`get(string $path): \Wolff\Core\Controller`

Returns a new instantiated controller.

```php
Controller::get('home');
```

That will return the home controller.

### Call controller method

`method(string $path, string $method[, array $args]): mixed`

Returns the value of a controller method.

The first parameter must be the controller name, the second parameter must be the method name, and the third (optional) parameter must be an array with the arguments that will be used for the method.

This method throws a `BadMethodCallException` when the method does not exists.

```php
Controller::method('client', 'getClientById', [ $client_id ]);
```

That will call the `getClientById` method of the `client` controller using the third parameter as the parameters.

### Exists

`exists(string $path): bool`

Returns `true` if the specified controller exists, `false` otherwise.

```php
Controller::exists('Home');
```

That will return `true` only if the `app/controllers/Home.php` controller exists, `false` otherwise.

### Has method

`hasMethod(string $path, string $method): bool`

Returns `true` if the specified method of the controller exists and is accessible, `false` otherwise.

The first parameter must be the controller name. The second parameter must be the method name.

```php
Controller::hasMethod('places/Info', 'getInfoById');
```

That will return `true` only if the `places/Info` controller class has a `getInfoById` method.
