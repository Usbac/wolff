`Wolff\Core\Controller`

The controllers are a great way to keep your code organized instead of defining everything in the `system/web.php` file.

The routing system is tied to the controllers.

## Usage

Let's create a controller, it must be in the `Controller` namespace.

Any public method is supposed to be accesible through a route, and it must take two parameters which are the request and response objects (instance of `Wolff\Core\Http\Request` and `Wolff\Core\Http\Response`).

The `index` method will be called by default when no method name is given.

app/controllers/home.php:

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

Given that example:

* `http://localhost/home` should render `hello world` in your browser.

* `http://localhost/home/sayHi` should render `hi`.

### Sub folders

You can store controllers in sub folders, if you put the above controller in an `app/controllers/sub` folder. It will be accessible through `http://localhost/sub/home`.

## General methods

The `Wolff\Core\Controller` class offers some useful static methods you can use.

### Get controller

`get(string $path)`

Returns a new instantiated controller.

```php
Controller::get('home');
```

That will return the home controller.

### Call controller method

`method(string $path[, string $method[, array $args]])`

Returns the value of a controller method.

The first parameter must be the controller name, the second parameter must be the method name, the third and optional parameter must be an array with the arguments that will be used for the method.

This method throws a `BadMethodCallException` when the method does not exists.

```php
Controller::method('client', 'getClientById', [ $client_id ]);
```

That will call the `getClientById` method of the `client` controller using the third parameter as the parameters.

### Exists

`exists(string $path)`

Returns `true` if the specified controller exists, `false` otherwise.

```php
Controller::exists('home');
```

That will return `true` only if the `app/controllers/home.php` controller exists, `false` otherwise.

### Has method

`hasMethod(string $path, string $method)`

Returns `true` if the specified method of the controller exists and is accessible, `false` otherwise.

The first parameter must be the controller name. The second parameter must be the method name.

```php
Controller::hasMethod('places/info', 'getInfoById');
```

That will return `true` only if the `places/info` controller class has a `getInfoById` method.
