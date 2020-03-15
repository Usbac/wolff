The routes can be managed in a traditional way where the last part of the route can be the controller or a function of the controller.

The following route `blog/post/`

Would load the `post.php` file inside the blog folder in the controller path.

So, the following route: `blog/post/sayHi`

Will call the `sayHi` function in the post controller class if it exists.

## Adding routes

Routes can also be managed in a cleaner and modern way. In the `system/Definitions/Routes.php` file you can define routes, its parameters and what to do.

The first parameter is the desired route, the second is the function that will be executed when accessing to the route, the third and optional parameter is the HTTP code.

The following block of code can be added to `system/Definitions/Routes.php`:

```php
Route::add('main_page', function() {
    Controller::call('home');
});
```

The same route but with a HTTP 301 response code:

```php
Route::add('main_page', function() {
	Controller::call('home');
}, 301);
```

It will load the `home` controller when accessing to `example.com/main_page`, obviously you can add more stuff to the function.

### Routes methods

#### Get route

```php
Route::get('main_page', function() {
	// Code
});
```

#### Post route

```php
Route::post('main_page', function() {
	// Code
});
```

#### Put route

```php
Route::put('main_page', function() {
	// Code
});
```

#### Patch route

```php
Route::patch('main_page', function() {
	// Code
});
```

#### Delete route

```php
Route::delete('main_page', function() {
	// Code
});
```

### Specifying content-type

You can specify the content type of the route using one of the following prefixes: `csv:`, `json:`, `pdf:`, `plain:` and `xml:`.

Example: 

```php
Route::get('json:users/get/{id}', function() {
	// Code
});
```

That will set the content-type of the route to `application/json`. If no content type is specified, by default `text/html` will be used.

## Routes by code

You can define routes that will be executed based on an HTTP status code using the `code` method.

```js
Route::code('404', function() {
        echo 'Not found :(';
});
```

That function will be executed only when the current status code is `404`.

## Controller loading

You can load a controller in a easier way if you pass a string as the second parameter. It'll be taken as the name of the controller which will be loaded.

```php
Route::add('main_page', 'home');
```

It will load the home controller when accessing to _example.com/main_page_.

## Route parameters

You can use get parameters in the URL

The following block of code

```php
Route::add('main_page/{name}', function() {
	echo Request::get('name');
});
```

Will take the second part of the route as a get variable which you can get from the common `$_GET` array using its name as the key, parameters should be put between brackets and only be alphanumeric characters.

### Optional parameters

You can also use optional get parameters in the URL

The following block of code

```php
Route::add('main_page/{name?}', function() {
	echo Request::get('name');
});
```

Will take the second part of the route as a get variable which you can get from the common `$_GET` array using its name as the key, parameters should be put between brackets, end with a question mark inside them and only be alphanumeric characters.

## Block

Routes can also be blocked using the block function. When blocking a page if anyone tries to gain access to it, they will be redirected to the 404 page.

The following block of code

```php
Route::block('main_page');
```

Will block the access to _example.com/main_page_ only

## Block recursively

You can block any sub route using the * symbol

```php
Route::block('main_page/contact/*');
```

This will block any route that has access to any `main_page/contact` subdirectory, keep in mind that `main_page/contact` itself will still be accessible

## Redirect

You can redirect one route to another. When doing it, a 301 HTTP response code will be returned.

```php
Route::redirect('page1', 'page2');
```

With this the route of page1 will redirect to page2, you can specify an HTTP response code if you want.

```php
Route::redirect('page1', 'page2', 200);
```

Will do the same but returning a 200 HTTP response code.

## Methods

The Route class has some useful methods that you can use.

### Route exists

Returns true if a view exists, false otherwise.

```php
Route::exists('home');
```

That will return true if the home route exists

### Get routes

Returns all the available routes.

```php
Route::getRoutes();
```

### Get redirections

Returns all the available redirections.

```php
Route::getRedirects();
```

### Get blocked

Returns all the blocked routes.

```php
Route::getBlocked();
```

_Keep in mind that all these get methods returns an associative array with the results._