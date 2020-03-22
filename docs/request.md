Instead of managing the superglobal arrays of PHP directly, Wolff gives you an abstraction layer throught the `Request` class.

## General methods

Just remember to `use Core\Request`.

### Get method

Returns the current request method. It is the equivalent to `$_SERVER['REQUEST_METHOD']`.

```php
Request::getMethod();
```

### Matches method

Returns true if the given method matches the current request method, false otherwise.

```php
Request::matchesMethod('POST');
```

That will return true if the current request method is POST.

*The parameter doesn't need to be in uppercase.*

## GET

### Get

Get a GET variable:

```php
Request::get('name');
```

If no parameter is passed to the function, it will retrieve all the `$_GET` array content.

### Has

Check if a GET variable exists:

```php
Request::hasGet('name');
```

### Set

Set a GET variable:

```php
Request::setGet('name', 'Margaret Brown');
```

### Unset

Unset a GET variable:

```php
Request::unsetGet('name');
```

If no parameter is passed to the function, all of the `$_GET` array content will be unset.


## POST

### Get

Get a POST variable:

```php
Request::post('name');
```

If no parameter is passed to the function, it will retrieve all the `$_POST` array content.

### Has

Check if a POST variable exists:

```php
Request::hasPost('name');
```

### Set

Set a POST variable:

```php
Request::setPost('name', 'Margaret Brown');
```

### Unset

Unset a POST variable:

```php
Request::unsetPost('name');
```

If no parameter is passed to the function, all of the `$_POST` array content will be unset.

## PUT

### Get

Get a PUT variable:

```php
Request::put('name');
```

If no parameter is passed to the function, it will retrieve all the PUT values.

### Has

Check if a PUT variable exists:

```php
Request::hasPut('name');
```

## PATCH

### Get

Get a PATCH variable:

```php
Request::patch('name');
```

If no parameter is passed to the function, it will retrieve all the PATCH values.

### Has

Check if a PATCH variable exists:

```php
Request::hasPatch('name');
```

## DELETE

### Get

Get a DELETE variable:

```php
Request::delete('name');
```

If no parameter is passed to the function, it will retrieve all the DELETE values.

### Has

Check if a DELETE variable exists:

```php
Request::hasDelete('name');
```

## FILES

### Get

Get a FILE variable:

```php
Request::file('name');
```

If no parameter is passed to the function, it will retrieve all of the `$_FILES` array content.

### Has

Check if a FILE variable exists:

```php
Request::hasFile('name');
```

## COOKIE

### Get

Get a COOKIE variable:

```php
Request::cookie('name');
```

If no parameter is passed to the function, it will retrieve all the `$_COOKIE` array content.

### Has

Check if a COOKIE variable exists:

```php
Request::hasCookie('name');
```

### Set

Set a COOKIE variable:

```php
Request::setCookie('name', 'value', 60);
```

The first parameter is the variable key, the second is the value, the third is the time expressed in seconds, the fourth and last value is the path where the variable will work, it's optional.

If the path is not specified, the cookie will be available in all of the page.

You can pass a string as the time too:

| String    | Time                |
| ----------|---------------------|
| forever   | 5 Years from now    |
| month     | 30 days from now    |
| day       | 24 hours from now   |

**Example**

```php
Request::setCookie('user_id', '1234', 'forever', 'profile/');
```

The `user_id` variable will be available for the next 5 years only in the 'profile' pages.

### Unset

Unset a COOKIE variable:

```php
Request::unsetCookie('name');
```

If no parameter is passed to the function, all of the `$_COOKIE` array content will be unset.