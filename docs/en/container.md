`Wolff\Core\Container`

Wolff includes a container which can be used to perform dependency injection on objects.

This is a great and clean way of defining new classes, since its dependencies are 'injected' into them and the instantiation only must be defined one time.

The container's methods are static, you can add and get elements anywhere inside Wolff, but it's recommended to add elements in the `system/web.php` file and get them when neccesary in your controllers.

## General methods

### Add

`add(string $class, $val): void`

Adds a new class to the container.

The first parameter is the class name.

The second parameter is the class value. This can be either a new instantiation of the class, or a function returning the class.

```php
Container::add('user', function() {
    return new \App\User();
});
```

```php
Container::add('user', new \App\User());
```

Both examples will work exactly the same.

_If a class with the same name already exists in the container, it will be overwritten._

### Add singleton

`singleton(string $class, $val): void`

Adds a new singleton class to the container.

When adding a singleton and calling it through the container, the container will make only one instance no matter how many time it's being called. Just like a singleton or a 'static class'.

```php
Container::singleton('user', function() {
    return new \App\User();
});
```

### Get

`get(string $key[, array $args]): mixed`

Returns the specified class instance.

```php
Container::get('user');
```

An optional second parameter can be pass to the method which must be an array with the parameters for the constructor of the class.

Defining the class:

```php
Container::add('user', function($params) {
    return new \App\User($params);
});
```

Getting the instance:

```php
$user = Container::get('user', [
    'John doe', 22
]);
```

That would be the equivalent to this:

```php
$user = new \App\User('John doe', 22);
```

### Has

`has(string $key): bool`

Returns `true` if the given class name exists, `false` otherwise.

```php
Container::has('user');
```

That will return `true` only if the `user` class has been added to the container.
