`Wolff\Core\Maintenance`

A Wolff project can enter into maintenance mode quite easily.

The constant `maintenance_on` defined in the `system/config.php` file indicates if the project is under maintenance or not, just change its value to `true` if you want to activate it.

When under maintenance, the function defined with the `set` method will be executed if the client IP address isn't in the white list.

### Is enabled

`isEnabled()`

Returns `true` if the maintenance mode is enabled, `false` otherwise.

```php
Maintenance::isEnabled();
```

### Set status

`setStatus([bool $enabled])`

Sets the status of the maintenance mode. `true` to enable it, `false` to disable it.

```php
Maintenance::setStatus(true);
```

### Use

`set(\Closure $func)`

Defines the function that will be executed under maintenance mode.

The function parameter must take two parameters which are the request object and the response object (instance of `Wolff\Core\Http\Request` and `Wolff\Core\Http\Response`).

```php
Maintenance::set(function($req, $res) {
    $res->write('Sorry, under maintenance :(. Come back later');
});
```

## White list

A white list file is the one that defines which IP address will still have access to the web app when it's under maintenance.

It should be located under the `system/` directory and should be named `maintenance_whitelist.txt`.

### Set white list file

`setFile([string $path])`

With the `setFile` method you can change the default white list file.

```php
Maintenance::setFile('system/ips.txt');
```

_The given path is relative to the project root folder._

### Add IP

`addAllowedIP(string $ip)`

Adds an IP address to the white list.

```php
Maintenance::addAllowedIP('127.0.0.1');
```

_If the white list file doesn't exists, it will be created automatically._

### Delete IP

`removeAllowedIP(string $ip)`

Deletes the given IP address from the white list.

```php
Maintenance::removeAllowedIP('127.0.0.1');
```

This method returns `true` if the IP has been removed or doesn't exists in the whitelist, `false` otherwise.

This method throws a `\Wolff\Exception\FileNotReadableException` when the whitelist file exists and is unreadable.

### Get IP list

`getAllowedIPs()`

Returns all the IP address in the white list.

```php
Maintenance::getAllowedIPs();
```

_This method returns the IP list as a non-associative array._

## Client Allowed

`hasAccess()`

Returns `true` if the current client IP address has access under maintenance mode and the maintenance mode is enabled, `false` otherwise.

```php
Maintenance::hasAccess();
```

