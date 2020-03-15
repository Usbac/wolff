The authentication utility simplifies the process of registering and login users into the database, meaning that you only have to worry about giving it the user data to register and/or login. 

It's built on top of the `\Core\DB` class so it uses PDO.

Just remember to `use Utilities\Auth`.

## General methods

### Register

Register a new user into the database.

This method takes as parameter an array which will be the user data to insert. The only required keys that the array must have are `password` and `password_confirm`, both values must be a string and equal.

This method returns true if the user has been successfully inserted into the database, false otherwise.

```php
$user = [
    'name'             => 'Alejandro',
    'email'            => 'contact@getwolff.com',
    'password'         => 'canislupus',
    'password_confirm' => 'canislupus',
];

Auth::register($user);
```

Take in consideration the following points:

* The password is hashed before storing it, using the `BCRYPT` algorithm with a default cost of 10.

* The array keys are directly maped to the database table (except for the `password_confirm` key). Meaning that an array with the following keys: `name`, `email`, `password` and `phone`, will be inserted into a table that must have a `name`, `email`, `password` and `phone` columns for this to work.

### Login

Returns true if the given user data exists in the database and is valid, false otherwise.

This method takes as parameter an array which will be the user data to validate. The only required key that the array must have is `password`.

```php
$user = [
    'email'    => 'contact@getwolff.com',
    'password' => 'canislupus'
];

Auth::login($user);
```

If a user with the giving email and password exists in the `user` table (in this example), it will return true.

### Set table

Set the name of the database table that will be used to register and login users. By default its value is `user`.

```php
Auth::setTable('admin');
```

### Get table

Get the name of the database table that will be used to register and login users.

```php
Auth::getTable();
```

### Set options

Set the options that will be used when hashing passwords. The parameter must be an associative array.

This is the equivalent to defining the third parameter of the `password_hash` function that is used inside the code of this class.

```php
$options = [
    'cost' => 16
];

Auth::setOptions($options);
```

That will set the cost of the pasword hashing function to 16. By default it's 10.

### Get options

Get the options that will be used when hashing passwords.

```php
Auth::getOptions();
```

### Get last inserted Id

Get the id of the last inserted/registered user into the database.

```php
Auth::getId();
```

### Get current user

Get the currently authenticated user data.

This function returns an associative array with the user data.

```php
Auth::getUser();
```

If no user has been logged in previously with the `login` method or if the last login attempt failed this will return `null`.