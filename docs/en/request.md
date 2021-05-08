`Wolff\Core\Http\Request`

Wolff offers you a quite useful and complete request object. It implements the `Wolff\Core\Http\RequestInterface` interface.

This can be done to avoid the superglobals of PHP, giving you a more object oriented syntax.

This request object is the one that must be passed as parameter to the Controller's public functions, route functions, and middleware functions.

### Route:
```php
Route::get('/', function($request) {
    ...
});

Route::code(404, function($request) {
    ...
});
```

### Middleware:
```php
Middleware::before('/', function($request) {
    ...
});
```

### Controller:
```php
namespace Controller;

class Home extends Controller
{
    public function index($request)
    {
        // Code
    }
}
```

## General methods

### Get parameter

`query([string $key]): mixed`

Returns the requested parameter (usually available in the `$_GET` superglobal array).

Given the route `localhost/wolff?foo=bar`.

```php
$request->query('foo');
```

It will return `bar`.

_If no parameter is passed, it will return an array with all the parameters._

### Has parameter

`hasQuery(string $key): bool`

Returns `true` if the given parameter key exists, `false` otherwise.

```php
$request->hasQuery('foo');
```

Given the route `localhost/wolff?foo=bar` it should return `true`.

### Get body parameter

`body([string $key]): mixed`

Returns the specified body parameter (usually available in the `$_POST` superglobal array).

```php
$request->body('username');
```

_If no parameter is passed, it will return an array with all the body parameters._

### Has body parameter

`has(string $key): bool`

Returns `true` if the given body parameter key exists, `false` otherwise.

```php
$request->has('username');
```

### Get file

`file([string $key]): mixed`

Returns the specified file (usually available in the `$_FILE` superglobal array). Returns 'false' if there is no `$key` specified, and no files are attached.

```php
$request->file('profile')
```

Keep in mind that these files are instances of `Wolff\Core\Http\File` (built from the `$_FILE` array). You can look at the `File` page of this documentation for more information.

_If no parameter is passed, it will return an array with all the files._

### Has file

`hasFile(string $key): bool`

Returns `true` if the given file key exists, `false` otherwise.

**Note**: This is not the filename, but the form field name where the file was uploaded.

```php
$request->hasFile('profile_image');
```

### File options

`fileOptions(array $arr): void`

Defines the options for uploading the request files, explained more in the file page of this documentation.

### Get cookie

`cookie([string $key]): mixed`

Returns the specified cookie (usually available in the `$_COOKIE` superglobal array).

```php
$request->cookie('user_session');
```

_If no parameter is passed, it will return an array with all the cookies._

### Has cookie

`hasCookie(string $key): bool`

Returns `true` if the given cookie key exists, `false` otherwise.

```php
$request->hasCookie('user_session');
```

### Get header

`getHeader([string $key]): mixed`

Returns the specified request header.

```php
$request->getHeader('Content-type');
```

_If no parameter is passed, it will return an array with all the headers._

### Get method

`getMethod(): string`

Returns the HTTP method.

```php
$request->getMethod();
```

In a request of type post it will return `POST`.

### Get Url

`getUri(): string`

Returns the request uri (without the query).

```php
$request->getUri();
```

### Get full Url

`getFullUri(): string`

Returns the full request uri.

```php
$request->getUri();
```

### Is secure

`isSecure(): bool`

Returns `true` if the current request protocol is secure (HTTPS), `false` otherwise.

```php
$request->isSecure();
```
