`Wolff\Core\Http\Response`

New responses can be made throught the Wolff Response class.

## Basics

Creating a new Response:

```php
$response = new Response();
```

The response methods can be chained, making the process of creating a new Response easier and quicker.

```php
$response->header('Content-Type', 'text/html; charset=utf-8')
         ->setCode(200)
         ->redirect('https://getwolff.com')
         ->go();
```

## General Methods

### Set HTTP code

`setCode([int $status])`

Sets the HTTP status code.

```php
$response->setCode(200);
```

### Add header

`header(string $key, string $value)`

Adds a new header to the response.

```php
$response->header('Content-Type', 'text/html; charset=utf-8');
```

### Remove header

`remove(string $key)`

Removes the specified header.

```php
$response->remove('Content-Type');
```

### Redirect url

`redirect(string $url[, int $status])`

Sets the Response's url.

```php
$response->redirect('https://getwolff.com');
```

The HTTP status code can be pass as an optional second parameter (this will overwrite the existing status code).

```php
$response->redirect('https://getwolff.com', 200);
```

### Go

`go()`

Executes the response with all of its values.

```php
$response->go();
```
