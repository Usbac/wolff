`Wolff\Core\Middleware`

The middleware system of Wolff is simple and quite useful. Its routing system works just like the route system.

## Adding Middlewares

There are two types of middlewares, those called before and after the request.

With that in mind, the middleware class has a `before` and `after` method to add respectively a middleware of type `before` and `after`.

`before(string $url, \Closure $func): void`  
`after(string $url, \Closure $func): void`

The function parameter must take two parameters, the Wolff request object (instance of `Wolff\Core\Http\Request`), and a function that when executed, will call the next middleware. So if this function isn't executed by that middleware, the middleware chain will stop right there.

The function parameter can return a string which will be appended to the response.

_Keep in mind that the middlewares are executed in the order they are added._

## Examples

```php
Middleware::before('admin/*', function($req, $next) {
    echo 'Entering in an admin page';
    $next();
});
```

That will render the text 'Entering in an admin page' for every page inside `admin`, like `admin/settings`, `admin/panel` or `admin/product/info`.


```php
Middleware::before('*', function($req, $next) {
    return 'Hello world';
});
```

That will show the 'Hello world' text for every page inside the app.

Here's a more expresive example:

```php
Middleware::after('home', function($req, $next) {
    echo 'Hello ';
    $next();
});

Middleware::after('home', function($req, $next) {
    echo 'World';
});

Middleware::after('home', function($req, $next) {
    echo 'This will NOT show up since the previous middleware didn\'t call next';
});
```

The above example will display the text 'hello world' when accessing to the `home` page.
