You can access to some useful methods related to the Controllers in the `Core\Controller` class.

## General methods

Just remember to `use Core\Controller`.

### Call

Returns a new instantiated controller.
This method calls the `index` function of the requested controller if it's defined.

```php
Controller::call('home');
```

That will return the home controller and run its `index` method

### Method

Returns the value of a controller method.
The first parameter must be the controller name, the second parameter must be the method name, the third and last parameter must be an array with the parameters that will be used for the method.

```php
Controller::method('client', 'getClientById', [ $client_id ]);
```

That will call the `getClientById` method of the `client` controller using the third parameter as the parameters.

### Closure

Appends a closure to a new controller and calls it.

If the given parameter is not a closure and it's a string, it will work like the `Controller::call` method.

```php
$func = function() {
    echo 'Hello World';
}

Controller::closure($func);
```

### Get path

Returns the file path of the given controller.

```php
Controller::getPath('sub/home');
```

By default that will return `app/controllers/sub/home.php`

### Exists

Returns true if the given controller file exists, false otherwise.

```php
Controller::exists('home');
```

That will return true only if the `app/controllers/home.php` file exists, false otherwise. 

### Method exists

Returns true if the method of a controller exists, false otherwise.

The first parameter must be the controller name. The second parameter must be the method name.

```php
Controller::methodExists('places/info@getInfoById');
```

That will return true only if the `places/info` controller class has a `getInfoById` method.