Instead of managing the `$_SESSION` variable directly, you can use the Wolff Session class.

All the session methods work with the time expressed in minutes (unless it’s specified here).

## General methods

Just remember to `use Core\Session`.

### Start session

Start the session:

```php
Session::start();
```

### Count session variables

Count the session variables:

```php
Session::count();
```

### Set session time

Set the session global time:

```php
Session::setTime(10);
```

### Get starting time

Get the session time (in seconds):

```php
Session::getStartTime();
```

Get session time with format (Hours, minutes and seconds):

```php
Session::getStartTime(true);
```

### Get remaining time

Get session remaining time (in seconds):

```php
Session::getRemainingTime();
```

Get session remaining time with format (Hours, minutes and seconds):

```php
Session::getRemainingTime(true);
```

### Unset

Unset session:

```php
Session::empty();
```

### Destroy

Destroy session:

```php
Session::kill();
```

## Variable methods

### Set

Declare a session variable:

```php
Session::set('name', $value);
```

### Get

Getting a session variable:

```php
Session::get('name');
```

### Has

Check if a session variable exists:

```php
Session::has('name');
```

### Unset

Unset a session variable:

```php
Session::unset('name');
```

### Get variable time

Getting variable time (in seconds):

```php
Session::getVarTime('name');
```

Getting variable time with format (Hours, minutes and seconds):

```php
Session::getVarTime('name', true);
```

### Set variable time

Set the variable live time:

```php
Session::setVarTime('name', 10);
```

### Add time to variable

Adding time to a variable:

```php
Session::addVarTime('name', 10);
```