`Wolff\Core\Route`

Routes can be managed in a clean and modern way with the Wolff route class.

In the `system/web.php` file you can define routes, their parameters and what they do.

## Adding routes

`any(string $url, $func[, int $status])`

The `any` method lets you add a route that will work for every HTTP method.

A basic route takes the first parameter as the desired route, the second as the `Closure` that will be called, and the third and optional parameter as the HTTP code that will be set when accessing that route.

The `Closure` can take two parameters which are the request and response objects (instance of `Wolff\Core\Http\Request` and `Wolff\Core\Http\Response`).

```php
Route::any('main_page', function($req, $res) {
    echo 'hello';
});
```

The same route but with a HTTP 301 response code:

```php
Route::any('main_page', function($req, $res) {
	echo 'hello';
}, 301);
```

It will display 'hello' when accessing to `example.com/main_page`.

### Routes methods

You can add routes that will work only for a specific HTTP method.

```php
Route::get($uri, $func, $method = 200);
Route::post($uri, $func, $method = 200);
Route::put($uri, $func, $method = 200);
Route::patch($uri, $func, $method = 200);
Route::delete($uri, $func, $method = 200);
```

## Binding methods

You can bind a class method to a route by passing an array as the second parameter. The first element of the array must be a string with the class name, the second must be a string with the method name. 

```php
Route::get('main_page', [ Controller\Home::class, 'index' ]);
```

In that case the `index` method of the `Home` controller will be called when accessing to `example.com/main_page`.

### View routes

`view(string $url, string $view[, array $data[, bool $cache]]): void`

You can add routes that render a view directly using the `view` method.

_Keep in mind that these routes will only be available through the GET method._

The second parameter must be the view path, the third the associative array with the content that will be used in the view, the fourth is to use or not the cache system.

This example will render the view `home` when accessing to the homepage page.

```php
Route::view('/', 'home', [
    'title' => 'Hello world',
]);
```

This example will render the `blog` view without using the cache system.

```php
Route::view('blog/list', 'blog', [], false);
```

### Specifying content-type

You can specify the content type header of the route by using one of the following prefixes: `csv:`, `json:`, `pdf:`, `plain:` and `xml:`.

Example:

```php
Route::get('json:users/{id}', function($req, $res) {
	// Code
});
```

That will set the content-type of the route to `application/json`.

## Routes by code

`code(int $code, $func): void`

You can define routes that will be executed based on an HTTP status code by using the `code` method.

The function parameter can take two parameters which are the request and response objects (instance of `Wolff\Core\Http\Request` and `Wolff\Core\Http\Response`).

```php
Route::code(404, function($req, $res) {
    $res->write('Not found :(');
});
```

That function will be executed only when the current status code is `404`.

## Route parameters

You can use get parameters in the URL.

The following block of code:

```php
Route::get('main_page/{name}', function($req, $res) {
	echo $req->query('name');
});
```

Will take the second part of the route as a get variable which you can get from the common `$_GET` array using its name as the key.

Parameters should be put between brackets and only be alphanumeric characters.

### Optional parameters

You can also use optional get parameters in the URL.

Parameters should be put between brackets, end with a question mark inside them and only be alphanumeric characters.

The following block of code:

```php
Route::get('main_page/{name?}', function($req, $res) {
	echo $req->query('name');
});
```

Will take the second part of the route as a get variable which you can get from the common `$_GET` array using its name as the key.

## Block

`block(string $url): void`

Blocks the given route, returning a 404 code when anyone tries to access to it.

```php
Route::block('main_page');
```

Will block the access to `example.com/main_page` only.

### Dynamic block

You can block any sub route using the `*` character.

```php
Route::block('main_page/contact/*');
```

This will block any route that has access to any `main_page/contact` subdirectory, keep in mind that `main_page/contact` itself will still be accessible.

## Redirect

`redirect(string $from, string $to[, int $code]): void`

You can redirect one route to another. When doing it, a 301 HTTP response code will be returned.

```php
Route::redirect('page1', 'page2');
```

With the above example the route `page1` will redirect to `page2`, you can specify an HTTP response code if you want.

```php
Route::redirect('page1', 'page2', 200);
```

That will do the same but will return a 200 HTTP response code.

### Dynamic redirect 

You can redirect any sub route using the `*` character.

```php
Route::redirect('posts/*', 'not_found', 200);
```

This will redirect any sub route of `posts` to `not_found`, keep in mind that `posts` itself won't be redirected.

## General Methods

The Route class has some useful methods that you can use.

### Route exists

`exists(string $url): bool`

Returns `true` if a view exists, `false` otherwise.

```php
Route::exists('home');
```

That will return `true` if the home route exists.

### Get routes

`getRoutes(): array`

Returns all available routes.

```php
Route::getRoutes();
```

### Get blocked

`getBlocked(): array`

Returns all the blocked routes.

```php
Route::getBlocked();
```

_Keep in mind that all of these 'get' methods return an associative array._
