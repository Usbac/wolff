`Wolff\Core\Log`

You can know what's happening in your Wolff project using the Logging class.

## Files

The logging files are located by default in the `system/logs` folder.

Every logging file represents a day and contains logs with the following format:

```
[date][ip] Level: message
```

## Logging

`{level}(string $msg[, array $values])`

Logging a simple information:

```php
Log::info('Welcome to Wolff.');
```

Obviously there are different levels for the messages, these are the available:

| Levels      |
| ------------|
| Emergency   |
| Alert       |
| Critical    |
| Error       |
| Warning     |
| Notice      |
| Info        |
| Debug       |

The same example showed above can be applied to those.

### Interpolation

The methods to logging data can take an optional second parameter which must be an associative array with values to interpolate in the string.

The values to interpolate must be between curly brackets.

```php
$values = [
    'name' => 'Thomas',
    'page' => 'home/'
];

Log::Debug('The current user is {name} in the page {page}', $values);
```

As an example, that should log `The current user is Thomas in the page home/`.

## General methods

### Is enabled

`isEnabled()`

Returns `true` if the log system is enabled, `false` otherwise.

```php
Log::isEnabled();
```

If the log system is disabled, nothing will happen when running the common log methods explained above.

### Set status

`setStatus([bool $enabled])`

Sets the status of the logging system. `true` to enable it, `false` to disable it.

```php
Log::setStatus(true);
```

### Set folder

`setFolder([string $folder])`

Sets the folder where the log files will be stored.

```php
Log::setFolder('app/logs');
```

_The given path is relative to the project root folder._

### Set date format

`setDateFormat([string $date_format])`

Sets the date format used internally in the log files.

The PHP [`date`](https://www.php.net/manual/en/function.date.php) function is used internally with the given date format. You can read the function's documentation for better understanding of the string you pass.

```php
Log::setDateFormat('H:i:s');
```

That will set the format like this: `Hour:minutes:seconds`.
