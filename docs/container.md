Wolff includes a container which can be used to perform dependency injection on objects.

When using it, if the instantiation of a class requires a modification, this must be done only in the container specification instead of in every place where the class is being instantiated.

This is a great and clean way of defining new classes, since it's dependencies are 'injected' into them and the instantiation only must be defined one time.

Just remember to `use Core\Container`.

## General methods

### Add

Add a new class to the container.

The first parameter is the class name.

The second parameter is the class value. So it can be either a new instantiation of the class, or a function returning the class.

```php
Container::add('\App\User', function() {
    return new \App\User();
});
```

```php
Container::add('\App\User', new \App\User());
```

Both examples will work exactly the same.

_If a class with the same name already exists in the container, it will be overwritten._

### Add singleton

Add a new singleton class to the container.

When adding a singleton and calling it throught the container, the container will make only one instance no matter how many time it's being called. Just like a singleton or a 'static class'.

```php
Container::singleton('\App\User', function() {
    return new \App\User();
});
```

### Get

Returns the specified class instance.

```php
Container::get('\App\User');
```

An optional second parameter can be pass to the method which must be an array with the parameters for the constructor of the class.

Defining the class:

```php
Container::add('\App\User', function($params) {
    return new \App\User($params);
});
```

Getting the instance:

```php
$user = Container::get('\App\User', [
    'John doe', 22
]);
```

That would be the equivalent to this: 

```php
$user = new \App\User('John doe', 22);
```

### Has

Returns true if the given class name exists, false otherwise.

```php
Container::has('\App\User');
```

That will return true only if the `\App\User` class has been added to the container.