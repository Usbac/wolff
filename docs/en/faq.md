**What is the framework approach?**

Simplify the process of building web applications with a small, fast and easy to use utility.

**What is the coding standard?**

The coding standard is [PSR-2](https://www.php-fig.org/psr/psr-2/).

**How is the release life cycle?**

Wolff strictly follows [Semantic Versioning](https://semver.org).

**How long does the support last for the versions?**

The last 2 major releases are the only ones supposed to get support.

So, if the last version is 3.x, support will be available for the 2.x and 3.x versions. If the last version is 4.x, support will be available for the 3.x and 4.x versions.

This condition is ignored ONLY if the major version that is going to be deprecated has been released less than a year ago. 

Basically any Wolff version will enjoy support for at least one year. 

But one goal of the framework is to do NOT release a major version unless there's a big issue in the current version that requires to break backward compatibility.

**What is the framework philosophy?**

The framework philosophy is to keep its simplicity while following good practices, clean code and an object oriented approach. All of this while trying to keep a stable architecture without relying on 'magic'.

**How does the framework compare with other alternatives**

Wolff stands between small frameworks like Slim and Lumen and big frameworks like Laravel, Codeigniter and Yii. It can be fast and small while having the right amount of functionality for building a web app. The utilities like the template engine, database abstraction layer and the standard library differentiates Wolff from micro frameworks (which commonly only have a router system and not much more).

Wolff doesn't follow the 'convenion over configuration' paradigm like Ruby on Rails or Laravel but it comes ready to be used and deployed due to its simplicity.

On the other hand, Wolff has a similarity with small open source projects and that's its lack of external dependencies.

**What is required to install Wolff?**

Only composer, but you can download the bundle versions that don't require it.

**What is required to run Wolff?**

You just need to have installed PHP version 7.1 or higher, if you are going to use any database functionality the php-pdo extension needs to be installed and enabled (since the database system is built on top of it). 

For running a Wolff project you can use either an Apache server or the common PHP built-in web server.

**What are the dependencies of the framework?**

One objective of Wolff is to avoid things like dependency hell or external libraries, the last is not strictly bad, it's just a decision of keeping all of the core functionality in one single module easy to modify, that's why the only dependency of a Wolff project is the framework itself (besides PHPUnit but it's used only for the tests).

**What are the recommended file permissions for Wolff?**

The recommended permissions are 0755 for folders and 0655 for PHP source files. For your safety, PHP files should be editable by the owner and readable by a group.

Avoid at any cost complete permissions (0777) in a production environment.

**What template engine is included with the framework?**

Wolff includes its own template engine, it has no name so it can be refered to as `Wolff template`.

**What are the Request and Response objects?**

The request and response objects can be used in the controller's methods, routes and middlewares, they are mentioned a lot in the documentation.

They are basically an abstraction layer built on top of PHP, with them you can follow a more object oriented approach while using PHP, with these objects you can avoid things like accessing directly to the superglobal PHP arrays or modifying the response code using the PHP functions.
