`Wolff\Utils\Auth`

The authentication utility simplifies the process of registering and login users into the database.

It's built on top of the `Wolff\Core\DB` class so it uses PDO.

_Keep in mind that the PHP PDO extension must be installed and enabled for the authentication utility to work._

## Starting

First you need to instantiate the `Auth` class, its constructor looks like this:

`__construct([array $data[, array $options]])`

It takes two parameters, an array with the destination database credentials, and an array which will be used as the options for the `password_hash` function that the utility uses internally.

If no data array is passed, it will use the credentials defined in the `system/config.php` file.

```php
$credentials = [
    'dsn'      => 'sqlite:example.db',
    'username' => 'root',
    'password' => '12345',
];

$options = [
    'cost' => 16
];

$auth = new \Wolff\Utils\Auth($credentials, $options);
```

## General methods

### Register

`register(array $data): bool`

Register a new user into the database.

The only required keys that the given array must have are `password` and `password_confirm`, both values must be a string and equal.

This method returns `true` if the user has been successfully inserted into the database, `false` otherwise.

```php
$auth->register([
    'name'             => 'Alejandro',
    'email'            => 'contact@getwolff.com',
    'password'         => 'canislupus',
    'password_confirm' => 'canislupus',
]);
```

Take in consideration the following points:

* The password is hashed before storing it, using the `BCRYPT` algorithm with a default cost of 10.

* The array keys are directly maped to the database table (except for the `password_confirm` key). Meaning that an array with the following keys: `name`, `email`, `password` and `phone`, will be inserted into a table that must have a `name`, `email`, `password` and `phone` columns for this to work.

### Login

`login(array $data): bool`

Returns `true` if the given user data exists in the database and is valid, `false` otherwise.

This method takes as parameter an array which will be the user data to validate. The only required key that the array must have is `password`.

```php
$auth->login([
    'email'    => 'contact@getwolff.com',
    'password' => 'canislupus',
]);
```

If a user with the giving email and password exists in the `user` table (like in the example), it will return `true`.

### Set table

`setTable([string $table]): void`

Sets the name of the database table that will be used to register and login users. By default its value is `user`.

```php
$auth->setTable('admin');
```

### Set unique column

`setUnique(string $unique_column): void`

Sets the name of the unique column that cannot be repeated when registering new users in the table. This function is available to avoid any duplicate entry.

```php
$auth->setUnique('email');
```

### Set options

`setOptions(array $options): void`

Set the options that will be used when hashing passwords.

This is the equivalent to defining the third parameter of the `password_hash` function that is used internally in this utility.

```php
$auth->setOptions([
    'cost' => 16
]);
```

That will set the cost of the pasword hashing function to 16. By default it's 10.

### Get options

`getOptions(): array`

Get the options that will be used when hashing passwords.

```php
$auth->getOptions();
```

### Get last inserted Id

`getId(): ?int`

Returns the id of the last inserted/registered user into the database.

```php
$auth->getId();
```

### Get current user

`getUser(): ?array`

Returns the currently authenticated user data.

This function returns an associative array with the user data.

```php
$auth->getUser();
```

This method will return `null` if no user has been logged in previously with the `login` method or if the last login attempt failed.
