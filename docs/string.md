In PHP, using functions related to strings is quite common, that's why Wolff includes a class with some functions related to strings which can be quite useful.

Just remember to `use Utilities\Str`.

## Sanitize

Sanitize strings is an important thing to do. So the String class have some functions related to it.

Sanitizing an URL:

```php
$url = Str::sanitizeURL($url);
//The function will return the $url variable sanitized
```

Exactly the same can be applied with the `sanitizeEmail`, `sanitizeInt`, `sanitizeFloat` and `sanitizePath` functions.

## Others

### Is email

Returns true if the given string complies with an email format.

```php
Str::isEmail('contact@getwolff.com');
```

That will return true.

### Is alphanumeric

Returns true if the given string contains only alphanumeric characters and whitespaces.

```php
Str::isAlphanumeric('abcdefg1234567 890');
```

That will return true.

### Is alpha

Returns true if the given string contains only letters and whitespaces.

```php
Str::isAlpha('abc def g');
```

That will return true.

### Token

Returns a random generated token.

```php
Str::token();
```

The default length of the token is 16 characters, but it can be changed passing a number as the only one parameter to that method.

```php
Str::token(24);
```

That will return a token with 24 characters length.

### Slug

Returns a url friendly string.

```php
Str::slug(' Hola cómo estás? Bien');
```

That will return `hola-como-estas-bien`.

Basically this function replaces special letters by their normal counterpart, puts everything lowercase and replaces the remaining characters with an hyphen `-`.


### Contains

Returns true if a string contains a substring.

```php
Str::contains('Lorem ipsum dolor sit amet', 'sit');
```

That will return true.

### Interpolate

Returns a string with its placeholders replaced by context values.

```php
$values = [
    'first' => 'john',
    'last'  => 'doe'
];

Str::interpolate('Your firstname is {first} and your lastname is {last}', $values);
```

That will return `Your firstname is john and your lastname is doe`.

_If the given array is empty, the method will return the original string._

### swap

Returns a string with the two indicated substrings swapped.

```php
Str::swap("I'm the Alpha, the Omega, everything in between", "Alpha", "Omega");
```

That will return `I'm the Omega, the Alpha, everything in between`.

The first parameter is the string, the remaining two are the substrings to be swapped.

### toUtf8

Converts a string with any encoding to UTF-8 and returns it.

Keep in mind that the string encoding detection is not perfect.

```php
Str::toUtf8($string);
```

### limit

Returns a truncated string with the specified length.

```php
Str::limit('Lorem ipsum dolor sit amet', '4');
```

That will return `Lore`.

### unShift

Adds the given value at the start of the string and returns it.

```php
Str::unShift('psum dolor sit amet', 'Lorem i');
```

That will return `Lorem ipsum dolor sit amet`.

### concat Path

Returns all the given strings and/or arrays of strings concatenated as a path.

```php
Str::concatPath('home', 'public', 'logo.svg');
```

```php
Str::concatPath(['home', 'public'], 'logo.svg');
```

Both examples are the same and will return `home/public/logo.svg`.

### concat

Returns all the given strings concatenated into one.

```php
Str::concat('Lorem ', 'ipsum ', 'dolor');
```

Returns `Lorem ipsum dolor`.

### toString

Returns the given value as a string.

```php
Str::toString(true);
//Returns 'true'
```

Booleans will be converted to a 'true' or 'false' string.

Arrays will be imploded to a normal string.

Numeric values will be converted to a string using the `strval` function.

### startsWith

Returns true if a string starts with a substring.

```php
Str::startsWith('Lorem ipsum dolor sit amet', 'Lorem');
```

That will return `true`.

### endsWith

Returns true if a string ends with a substring.

```php
Str::endsWith('Lorem ipsum dolor sit amet', 'amet');
```

That will return `true`.

### remove

Removes all the ocurrences of a subtring in a string.

The first parameter is the original string, the second is the substring to remove.

```php
Str::remove('Lorem ipsum dolor sit amet', 'sit');
```

That will return `Lorem ipsum dolor  amet`.

### after

Returns everything after the specified substring.

The first parameter is the original string, the second is the substring that will cut it.

```php
Str::after('Lorem ipsum dolor sit amet', 'dolor');
```

That will return ` sit amet`.

### before

Returns everything before the specified substring.

The first parameter is the original string, the second is the substring that will cut it.

```php
Str::before('Lorem ipsum dolor sit amet', 'dolor');
```

That will return `Lorem ipsum `.

### pathToNamespace

Turns a directory path into a namespace path, it replaces slashes by backslashes.

```php
pathToNamespace('sub/home');
```

That will return `sub\home`.

### namespaceToPath

Turns a namespace path into a directory path, it replaces backslashes by slashes.

```php
namespaceToPath('sub\home');
```

That will return `sub/home`.
