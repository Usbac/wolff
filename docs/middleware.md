The middleware system of Wolff is simple and useful for some especial and specific tasks.

The middleware folder should be `app/middlewares`.

A middleware file must have the following format:

```php
namespace Middleware;

class {filename} {

    public $desc = [
        'name'        => 'The extension name',
        'description' => 'The extension description'
    ];    


    public function index() {
        //Code
    }

}
```

Keep in mind that the index method is called when the middleware is loaded.

The middleware filename must match the class name inside it and the file must be inside the middleware folder (it's created automatically if it doesn't exists).

## Adding Middlewares

The middleware routing system works like the route system.

A file named `Middlewares.php` located in the `system/Definitions` folder is used to define the middlewares and the routes where they will work.

The file structure looks like this:

```php
namespace System;

use Core\Middleware;

/**
 * Use this file for declaring routes, blocks, redirections and more...
 */

Middleware::before('*', 'example');
```

Adding an existing middleware:

```php
Middleware::before('*', 'sayHi');
```

The first parameter is the working route, the second one is the middleware name.

This will load the sayHi middleware in every page before loading them, you can use the `after` method for calling that middleware after the page is loaded.

Examples:

```php
Middleware::before('homepage', 'sayHi');
/* The middleware will be executed only when the user access to 
 * example.com/homepage.
 */

Middleware::before('homepage/*', 'sayHi');
/* The middleware will be executed only when the user access to any homepage sub route like 
 * example.com/homepage/contact or example.com/homepage/user.
 */

Middleware::before('homepage/{}', 'sayHi');
/* The middleware will be executed only when the user access to the homepage route 
 * followed by a get variable, like example.com/homepage/2, example.com/homepage/3 and others.
 */
```

If the route parameter is set to empty, the middleware will be disabled (won't load in any page).

If the route parameter is set to '*', the middleware will work in every page.

## Example

A simple middleware named SayHello that says Hello two times in every page. 

The name of this file is `sayHello.php` and is inside the middleware folder

```php
namespace Middleware;

class sayHello {

    public $desc = [
        'name'        => 'Hello',
        'description' => 'Say hello two times to everyone',
    ];    


    public function index() {
        for ($i = 0; $i < 2; $i++) {
            echo "</br> Hello";
        }
    }

}
```

## General methods

The middleware class has some useful methods.

### Get middleware folder

Get the middleware folder path:

```php
Middleware::getFolder();
```

### Create middleware folder

Create the middleware folder if doesn't exists (it is done automatically anyway):

```php
Middleware::mkdir();
```

### Middleware details

Get all the middlewares details:

```php
Middleware::get();
```

It will return an array with the name and description of every middleware.
If you create the `sayHello.php` middleware that was shown above, it will return the following result:

```php
Array
(
    [0] => Array
        (
            [filename] => sayHello
            [name] => Hello
            [description] => Say hello two times to everyone
        )

)
```

You can also pass an middleware name as parameter and it will return only the information of the specified middleware:

```php
Middleware::get('sayHello');
```
