You can know what's happening in your Wolff project using the Logging class. 

Just remember to `use Core\Log`.

## Files

The logging files are located by default in the `system/logs` folder.

Every logging file represents a day and contains logs with the following format:

```
[date] [ip] level: message
```

## Methods

### Is enabled

You can check whetever or not the log system is enabled with the `isEnabled` method.

```php
Log::isEnabled();
```

If the log system is disabled, nothing will happen when running the common log methods explained below.

### Logging

Logging a simple information:

```php
Log::info('Welcome to Wolff.');
```

The methods to logging data can take an optional second parameter which must be an associative array with values to interpolate in the string. This works with the `\Utilities\Str` interpolate method.

```php
$values = [
    'name' => 'Thomas',
    'page' => getCurrentPage()
];

Log::Debug('The current user is {name} in the page {page}', $values);
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

The same example showed above can be applied to these.