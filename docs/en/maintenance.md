`Wolff\Core\Maintenance`

A Wolff project can enter into maintenance mode quite easily.

The constant `maintenance_on` defined in the `system/config.php` file indicates if the project is under maintenance or not, just change its value to `true` if you want to activate it.

When under maintenance, the function defined with the `set` method will be executed if the client IP address isn't in the white list, and its access to the web app will be blocked.

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

A white list is the one that defines which IP addresses will still have access to the web app when it's under maintenance.

### Set IPs white list

`setIPs(iterable $ips)`

Sets the IPs whitelist.

The given parameter must be an iterable (meaning it can be an array or an object implementing the Traversable interface).

```php
Maintenance::setIPs([
    '192.168.2.150',
    '::1',
]);
```

Now the IPs `192.168.2.150` and `::1` will have access even on maintenance mode.

### Delete white list IP

`removeIP(string $ip)`

Deletes the given IP address from the white list.

```php
Maintenance::removeAllowedIP('127.0.0.1');
```

### Get IP white list

`getIPs()`

Returns all the IP address in the white list.

```php
Maintenance::getIPs();
```

_This method returns the IP list as an indexed array._

## Client has access

`hasAccess()`

Returns `true` if the current client IP address is in the white list, `false` otherwise.

```php
Maintenance::hasAccess();
```
