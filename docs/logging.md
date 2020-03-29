`Wolff\Core\Log`

You can know what's happening in your Wolff project using the Logging class.

## Files

The logging files are located by default in the `system/logs` folder (it's defined in the `system/config.php` file).

Every logging file represents a day and contains logs with the following format:

```
[date] [ip] level: message
```

### Is enabled

`isEnabled()`

Returns `true` if the log system is enabled, `false` otherwise.

```php
Log::isEnabled();
```

If the log system is disabled, nothing will happen when running the common log methods explained below.

### Logging

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
    'page' => getCurrentPage()
];

Log::Debug('The current user is {name} in the page {page}', $values);
```

As an example, that should log `The current user is Thomas in the page home/`.
