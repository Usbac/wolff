`Wolff\Core\Http\Response`

New responses can be made throught the Wolff Response class.

This can be done to avoid the superglobals of PHP, giving you a more object oriented syntax.

This response object is the one that must be passed as second parameter to the Controller's public functions, route functions, and middleware functions.

### Route:
```php
Route::get('/', function($request, $response) {
});

Route::code(404, function($request, $response) {
});
```

### Middleware:
```php
Middleware::before('/', function($request, $response) {
});
```

### Controller:
```php
namespace Controller;

class Home extends Controller
{
    public function index($request, $response)
    {
    }
}
```

After the current controller method or route function is finished, the `send` method of the response object is called automatically, meaning that you don't have to worry about calling it in your code.

## Basics

Creating a new Response.

```php
$response = new Wolff\Core\Http\Response();
```

All of the response methods can be chained, making the process easier and quicker.

```php
$response->setHeader('Content-Type', 'text/html; charset=utf-8')
         ->write('Hello world')
         ->setCode(200)
         ->setCookie('logged_in', 'yes')
         ->send();
```

## General Methods

### Write content

`write($content)`

Sets the content that will be returned by the response.

```php
$response->write('Hello world');
```

That would be the equivalent to the classic and ugly: `echo 'hello world';`.

_The string value of the given variable is the one that will be returned._

### Append content

`append($content)`

Appends content to the current content that will be returned by the response.

```php
$response->append('How are you?');
```

_The string value of the given variable is the one that will be returned._

### Set HTTP code

`setCode([int $status])`

Sets the HTTP status code.

```php
$response->setCode(200);
```

### Add header

`setHeader(string $key, string $value)`

Adds a new header to the response.

```php
$response->setHeader('Content-Type', 'text/html');
```

### Add cookie

`setCookie(string $key, string $value[, $time[, string $path[, string $domain[, bool $secure[, bool $http_only]]]]])`

Adds a new cookie to the response.

```php
$response->setCookie('has_recent_activity', '1');
```

More expresive example.

```php
$response->setCookie('user_session', 'Zcv6ys3dgluw', 60, '/', true, true);
```

### Remove cookie

`unsetCookie(string $key)`

Removes a cookie.

```php
$response->unsetCookie('user_session');
```

### Send response

`send()`

Sends the response with all of its values.

```php
$response->send();
```
