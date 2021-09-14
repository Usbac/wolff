<h1 align="center">
  <img src="http://getwolff.com/logo.png" alt="Wolff logo" width="200">
  <br>
  Wolff
  <br>
</h1>

<h4 align="center">Web development made just right.</h4>

<p align="center">
<img src="https://travis-ci.org/Usbac/wolff.svg?branch=master">
<img src="https://img.shields.io/badge/stable-4.1.0-blue.svg">
<img src="https://img.shields.io/badge/license-MIT-orange.svg">
</p>

Wolff is a ridiculously small and lightweight PHP framework, intended for those who want to build web apps without dealing with complexity. 

Wolff is the perfect solution for building small and medium-sized web applications.

> **Note:** The core code of the framework is available at [Wolff-framework](https://github.com/usbac/wolff-framework).

## Features

📓 **Extremely easy**: It's simple to use and comes ready to be deployed, giving you the opportunity to learn it in just a single night (sleep included).

🚀 **Ridiculously fast**: Due to its simplicity, the framework is able to handle hundreds of requests per second. It's very friendly with potato servers.

🛠️ **Comprenhensive**: It has everything you may need for building a web app, from a handy database abstraction layer to a dynamic template engine.

🦄 **Elegant**: Its source code and interface has been meticulously written, it's expressive and elegant, without depending on a single external dependency.

## What's included

* [**Database abstraction layer**](https://getwolff.com/doc/3.x/db)

* [**DI Container**](https://getwolff.com/doc/3.x/container)

* [**Language manager**](https://getwolff.com/doc/3.x/language)

* [**Routing system**](https://getwolff.com/doc/3.x/routes)

* [**Standard library**](https://getwolff.com/doc/3.x/stdlib)

* [**Template engine**](https://getwolff.com/doc/3.x/template)

And **much** more...

## Requirements

* PHP version 7.1 or higher

* Composer (Only for installation)

## Install

[Composer](https://getcomposer.org/) is required for installing Wolff, once you got it...

Run the following command in the folder where you want Wolff to be installed:

```
composer create-project usbac/wolff
```

This will download the whole project with everything ready to run.

More info about the installation process in the [Docs - install](https://getwolff.com/doc/4/installation) page.

### Bundle

_You can also download [here](https://github.com/Usbac/wolff/releases/download/v4.1.0/wolff-bundle.zip) the last bundle ready to be used._

## Example

app/controllers/home.php:
```php
‹?php

namespace Controller;

use Wolff\Core\{Language, View};

class Home
{
    public function index($req, $res)
    {
        $data = Language::get('home');    
        View::render('home', $data);
    }
}
```

More examples available [here](http://getwolff.com/doc/4/starting).

## Testing

[PHPUnit](https://phpunit.de) is required for the tests, once you got it.

Run the following command with high privileges (sudo) in your wolff project folder:

```
vendor/bin/phpunit
```

_Running the command with high privileges is required since some files will be created during the testing process._

## Documentation

First time using it? Read the [Documentation](https://getwolff.com/doc/4/home).

## Contributing

Any contribution or support to this project in the form of a pull request or message will be highly appreciated. ❤️

You can read more about the contribution process [right here](CONTRIBUTING.md). Don't be shy. :)

## License

Wolff is open-source software licensed under the [MIT license](LICENSE).
