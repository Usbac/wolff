A Wolff project can enter into maintenance mode quite easily.

The constant `maintenance_on` defined in the `config.php` file indicates if the project is under maintenance or not, just change its value to `true` if you want to activate it.

When under maintenance, a default `_maintenance.php` controller will be called if the client IP address isn't in the white list.

## White list 

A white list file is the one that defines which IP address will still have access to the page content when it's under maintenance.

It should be located under the `system/definitions` directory and should be named `maintenance_whitelist.txt`.

You can create it in a fast way calling the `createFile` method:

```php
Maintenance::createFile();
```

### Add IP

Adding an IP address to the white list:

```php
Maintenance::addAllowedIP('127.0.0.1');
```

_If the white list file doesn't exists, it will be created automatically._

### Delete IP

Deleting an IP address from the white list:

```php
Maintenance::removeAllowedIP('127.0.0.1');
```

### Get IP list

Get all the IP address in the white list:

```php
Maintenance::getAllowedIPs();
```

_This will return the IP list as an array._

## Client Allowed

You can know if the current client IP address is in the white list with the following method:

```php
Maintenance::isClientAllowed();
```

**Remember that you can add and remove IPs using the CLI of Wolff too**.