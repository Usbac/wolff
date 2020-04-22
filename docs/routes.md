`Wolff\Core\Route`

Routes can be managed in a clean and modern way with the Wolff route class.

In the `system/web.php` file you can define routes, its parameters and what to do.

## Adding routes

`any(string $url, $function[, int $status])`

The `any` method let's you add a route that will work in any http method.

The first parameter is the desired route, the second is the function that will be executed when accessing to the route, the third optional parameter is the HTTP code.

The function parameter must take two parameters which are the request and response object (instance of `Wolff\Core\Http\Request` and `Wolff\Core\Http\Response`).

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

You can add routes that will work only for a specific http method.

```php
Route::get($uri, $function, $method = 200);
Route::post($uri, $function, $method = 200);
Route::put($uri, $function, $method = 200);
Route::patch($uri, $function, $method = 200);
Route::delete($uri, $function, $method = 200);
```

### View routes

`view(string $url, string $view_path[, array $data[, bool $cache]])`

You can add routes that render a view directly using the `view` method.

_Keep in mind that these routes will only be available through the GET method._

The second parameter must be the view path, the third the associative array with the content that will be used in the view, the fourth is to use or not the cache system.

This example will render the view `home` when accessing to the homepage page.

```php
$data = [
    'title' => 'Hello world'
];

Route::view('/', 'home', $data);
```

This example will render the `blog` view without using the cache system.

```php
Route::view('blog/list', 'blog', [], false);
```

### Specifying content-type

You can specify the content type of the route using one of the following prefixes: `csv:`, `json:`, `pdf:`, `plain:` and `xml:`.

Example:

```php
Route::get('json:users/{id}', function($req, $res) {
	// Code
});
```

That will set the content-type of the route to `application/json`.

## Routes by code

`code(int $code, $function)`

You can define routes that will be executed based on an HTTP status code using the `code` method.

The function parameter must take two parameters which are the request object and the response object (instance of `Wolff\Core\Http\Request` and `Wolff\Core\Http\Response`).

```php
Route::code(404, function($req, $res) {
    $res->write('Not found :(');
});
```

That function will be executed only when the current status code is `404`.

## Controller loading

You can load a controller in a easier way if you pass a string as the second parameter.

The string must follow this syntax: `controller_path@method`.

```php
Route::get('main_page', 'home@index');
```

It will load the index method of home controller when accessing to `example.com/main_page`.

## Route parameters

You can use get parameters in the URL

The following block of code

```php
Route::get('main_page/{name}', function($req, $res) {
	echo $req->query('name');
});
```

Will take the second part of the route as a get variable which you can get from the common `$_GET` array using its name as the key.

Parameters should be put between brackets and only be alphanumeric characters.

### Optional parameters

You can also use optional get parameters in the URL

The following block of code

```php
Route::get('main_page/{name?}', function($req, $res) {
	echo $req->query('name');
});
```

Will take the second part of the route as a get variable which you can get from the common `$_GET` array using its name as the key.

Parameters should be put between brackets, end with a question mark inside them and only be alphanumeric characters.

## Block

`block(string $url)`

Blocks the given route, returning a 404 code when anyone tries to access to it.

```php
Route::block('main_page');
```

Will block the access to `example.com/main_page` only.

### Block recursively

You can block any sub route using the * symbol

```php
Route::block('main_page/contact/*');
```

This will block any route that has access to any `main_page/contact` subdirectory, keep in mind that `main_page/contact` itself will still be accessible.

## Redirect

`redirect(string $from, string $to[, int $code])`

You can redirect one route to another. When doing it, a 301 HTTP response code will be returned.

```php
Route::redirect('page1', 'page2');
```

With this the route of page1 will redirect to page2, you can specify an HTTP response code if you want.

```php
Route::redirect('page1', 'page2', 200);
```

Will do the same but returning a 200 HTTP response code.

## General Methods

The Route class has some useful methods that you can use.

### Route exists

`exists(string $url)`

Returns true if a view exists, false otherwise.

```php
Route::exists('home');
```

That will return true if the home route exists

### Get routes

`getRoutes()`

Returns all the available routes.

```php
Route::getRoutes();
```

### Get redirections

`getRedirections()`

Returns all the available redirections.

```php
Route::getRedirects();
```

### Get blocked

`getBlocked()`

Returns all the blocked routes.

```php
Route::getBlocked();
```

_Keep in mind that all these get methods returns an associative array with the results._
