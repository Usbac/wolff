New responses can be made throught the Wolff `Response` class. 

Remember to `use Core\Response`.

## Basics

Creating a new Response:

```php
$response = new Response();
```

The `header`, `remove`, `setCode` and `redirect` methods can be used as chained methods.
Making the process of creating a new Response easier and quicker.

```php
$response->header('Content-Type', 'text/html; charset=utf-8')
         ->setCode(200)
         ->redirect('https://getwolff.com')
         ->go();
```

## Methods

### Set HTTP code

Set the HTTP status code.

```php
$response->setCode(200);
```

### Get HTTP code

Returns the response HTTP status code.

```php
$response->getCode();
```

### Get redirect url

Returns the response url.

```php
$response->getRedirect();
```

### Add header

Add a new header to the response.

```php
$response->header('Content-Type', 'text/html; charset=utf-8');
```

The first parameter is the header's key, the second is the header's value.

### Get headers

Returns all the response headers (as an associative array).

```php
$response->getHeaders();
```

### Remove header

Remove a header if it exists.

```php
$response->remove('Content-Type');
```

The parameter must be the desired header's key to delete.

### Redirect url

Set the Response's url. 

```php
$response->redirect('https://getwolff.com');
```

The HTTP status code can be pass as an optional second parameter (this will overwrite the existing status code).

```php
$response->redirect('https://getwolff.com', 200);
```

### Go

Execute the response with all of its values.

```php
$response->go();
```