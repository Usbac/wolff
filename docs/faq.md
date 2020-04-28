## What is the framework approach?

Simplify the process of building web applications with a small, fast and easy to use utility.

## What is the coding standard?

The coding standard is [PSR-2](https://www.php-fig.org/psr/psr-2/).

## How is the release life cycle?

Wolff strictly follows [Semantic Versioning](https://semver.org).

## What is the framework philosophy?

The framework philosophy is to keep its simplicity while following good practices, clean code and an object oriented approach. All of this without relying on 'magic'.

## What is required to install Wolff?

Only composer, but you can download the full bundle version that doesn't require it.

## What is required to run Wolff?

You just need to have installed PHP version 7 or higher, if you are going to use any database functionality the php-pdo extension needs to be installed and enabled (since the database system is built on top of it). 

For running a Wolff project you can use either an Apache server or the common PHP build-in web server.

## What are the dependencies of the framework?

One objective of Wolff is to avoid things like dependency hell or external libraries, the last is not strictly bad, it's just a decision of keeping all of the core functionality in one single module easy to modify, that's why the only dependency of a Wolff project is the framework itself (besides PHPUnit which is used only for the tests). 

## What template engine is included with the framework?

Wolff includes its own template engine, it has no name so it can be refered to as `Wolff template`.

## What are the Request and Response objects?

The request and response objects can be used in the controller's methods, routes and middlewares, they are mentioned a lot in the documentation.

They are basically an abstraction layer built on top of PHP, with them you can follow a more object oriented approach while using PHP, with these objects you can avoid things like accessing directly to the superglobal PHP arrays or modifying the response code using the PHP functions.
