`Wolff\Core\Log`

You can know what's happening in your Wolff project using the Logging class.

The methods of the Logging class can be called anywhere inside your Wolff project.

## Files

The logging files are located by default in the `system/logs` folder.

Every logging file represents a day and contains logs with the format `[date][ip] [level]: [message]`.

## Logging

These are the available logging methods:

`emergency(string $msg[, array $values]): void`
`alert(string $msg[, array $values]): void`
`critial(string $msg[, array $values]): void`
`error(string $msg[, array $values]): void`
`warning(string $msg[, array $values]): void`
`notice(string $msg[, array $values]): void`
`info(string $msg[, array $values]): void`
`debug(string $msg[, array $values]): void`

The same example showed below can be applied to the other methods.

```php
$log = new Log();
$log->info('Welcome to Wolff.');
```

That will log the message 'Welcome to Wolff' as an info.

### Interpolation

The methods to logging data can take an optional second parameter which is an associative array with values to interpolate in the string.

The values to interpolate must be between curly brackets like `{this}`.

```php
$values = [
    'name' => 'Thomas',
    'page' => 'home/'
];

$log->debug('The user is {name} in the page {page}', $values);
```

As an example, that should log `The user is Thomas in the page home/`.

## General methods

### Is enabled

`isEnabled(): bool`

Returns `true` if the log system is enabled, `false` otherwise.

```php
$log->isEnabled();
```

If the log system is disabled, nothing will happen when running the common log methods explained above.

### Set status

`setStatus([bool $enabled]): void`

Sets the status of the logging system. `true` to enable it, `false` to disable it.

```php
$log->setStatus(true);
```

### Set folder

`setFolder([string $folder]): void`

Sets the folder where the log files will be stored.

```php
$log->setFolder('app/logs');
```

_The given path is relative to the project root folder._

### Set date format

`setDateFormat([string $format]): void`

Sets the date format used internally in the log files.

The PHP [`date`](https://www.php.net/manual/en/function.date.php) function is used internally with the given date format. You can read the function's documentation for better understanding of the string you pass.

```php
$log->setDateFormat('H:i:s');
```

That will set the format like this: `Hour:minutes:seconds`.
