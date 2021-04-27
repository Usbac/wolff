`Wolff\Utils\Str`

In PHP, using functions related to strings is quite common, that's why Wolff includes a class with some functions related to strings which can be quite useful.

## General Methods

### Sanitize

Sanitize strings is an important thing to do. So the String class have some functions related to it.

`sanitizeUrl(string $url): string`  
`sanitizeEmail(string $email): string`  
`sanitizeInt(string $int): string`  
`sanitizeFloat(string $float): string`  
`sanitizePath(string $path): string`  

Example:

```php
$url = Str::sanitizeURL($url);
```

### Is Email

`isEmail(string $email): bool`

Returns `true` if the given string complies with an email format, `false` otherwise.

```php
Str::isEmail('contact@getwolff.com');
```

That will return `true`.

### Is Alphanumeric

`isAlphanumeric(string $str): bool`

Returns `true` if the given string contains only alphanumeric characters and whitespaces, `false` otherwise.

```php
Str::isAlphanumeric('abcdefg1234567 890');
```

That will return `true`.

### Is Alpha

`isAlpha(string $str): bool`

Returns `true` if the given string contains only letters and whitespaces, `false` otherwise.

```php
Str::isAlpha('abc def g');
```

That will return true.

### Remove quotes

`removeQuotes(string $str): string`

Returns the given string without the single or double quotes surrounding it.

```php
Str::removeQuotes('"Hello world"');
```

That should return the string 'Hello world'.

_Keep in mind that the quotes will be removed only if they surround the string on both sides, meaning that passing a string like 'Hello world"' will make the method return the same string._

### Token

`token([int $length]): string`

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

`slug(string $str): string`

Returns a url friendly string.

```php
Str::slug(' Hola cómo estás? Bien');
```

That will return `hola-como-estas-bien`.

Basically this function replaces special letters by their normal counterpart, puts everything lowercase and replaces the remaining characters with an hyphen `-`.

### Contains

`contains(string $str, string $needle): bool`

Returns `true` if a string contains a substring, `false` otherwise.

```php
Str::contains('Lorem ipsum dolor sit amet', 'sit');
```

That will return `true`.

### Interpolate

`interpolate(string $str, array $values): string`

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

### Swap

`swap(string $str, string $first_str, string $second_str): string`

Returns a string with the two indicated substrings swapped.

```php
Str::swap("I'm the Alpha, the Omega, everything in between", "Alpha", "Omega");
```

That will return `I'm the Omega, the Alpha, everything in between`.

The first parameter is the string, the remaining two are the substrings to be swapped.

### Limit

`limit(string $str, int $limit): string`

Returns a truncated string with the specified length.

```php
Str::limit('Lorem ipsum dolor sit amet', '4');
```

That will return `Lore`.

### Concatenate Path

`concatPath(...$paths): string`

Returns all the given strings and/or arrays of strings concatenated as a path.

```php
Str::concatPath('home', 'public', 'logo.svg');
```

```php
Str::concatPath(['home', 'public'], 'logo.svg');
```

Both examples are the same and will return `home/public/logo.svg`.

### Concatenate

`concat(...$strings): string`

Returns all the given strings concatenated into one.

```php
Str::concat('Lorem ', 'ipsum ', 'dolor');
```

Returns `Lorem ipsum dolor`.

### To String

`toString($var): string`

Returns the given value as a string.

```php
Str::toString(true);
```

That will return 'true'.

Booleans will be converted to a 'true' or 'false' string.

Arrays will be imploded to a normal string.

Numeric values will be converted to a string using the `strval` function.

### Starts with

`startsWith(string $str, string $needle): bool`

Returns `true` if a string starts with a substring, `false` otherwise.

```php
Str::startsWith('Lorem ipsum dolor sit amet', 'Lorem');
```

That will return `true`.

### Ends with

`endsWith(string $str, string $needle): bool`

Returns `true` if a string ends with a substring, `false` otherwise.

```php
Str::endsWith('Lorem ipsum dolor sit amet', 'amet');
```

That will return `true`.

### Remove substring

`remove(string $str, string $needle): string`

Removes all the ocurrences of a subtring in a string.

The first parameter is the original string, the second is the substring to remove.

```php
Str::remove('Lorem ipsum dolor sit amet', 'sit');
```

That will return `Lorem ipsum dolor  amet`.

### After substring

`after(string $str, string $needle): string`

Returns everything after the specified substring.

The first parameter is the original string, the second is the substring that will cut it.

```php
Str::after('Lorem ipsum dolor sit amet', 'dolor');
```

That will return ` sit amet`.

### Before substring

`before(string $str, string $needle): string`

Returns everything before the specified substring.

The first parameter is the original string, the second is the substring that will cut it.

```php
Str::before('Lorem ipsum dolor sit amet', 'dolor');
```

That will return `Lorem ipsum `.
