<h1 align="center">
  <img src="http://getwolff.com/logo.png" alt="Wolff logo" width="200">
  <br>
  Wolff
  <br>
</h1>

<h4 align="center">Web development made just right.</h4>

<p align="center">
<img src="https://travis-ci.org/Usbac/wolff.svg?branch=master">
<a href="https://packagist.org/packages/usbac/wolff"><img src="https://poser.pugx.org/usbac/wolff/d/total.svg"></a>
<img src="https://img.shields.io/badge/stable-3.2.2-blue.svg">
<img src="https://img.shields.io/badge/license-MIT-orange.svg">
</p>

Wolff is a ridiculously small and lightweight PHP framework, intended for those who want to build web applications without starting from scratch or dealing with complexity. 

Wolff is the perfect solution for building small and medium-sized web applications.

> **Note:** The core code of the framework is available at [Wolff-framework](https://github.com/usbac/wolff-framework).

## Features

📓 **Extremely easy**: It's simple to use and has a clean documentation and interface. It gives you the opportunity to learn it in just a single night (sleep included).

🚀 **Ridiculously fast**: If a resource is not used, it's not loaded. Some elements of the framework can even be disabled. Wolff is very friendly with potato servers and works seamlessly.

🛠️ **Comprenhensive**: Wolff has elements that cover everything you may ever need for building a web app. Absolutely no initial setup is required. They are ready to use.

## What's included

* [**Database abstraction layer**](https://github.com/Usbac/wolff/wiki/Database)

* [**DI Container**](https://github.com/Usbac/wolff/wiki/Container)

* [**Language manager**](https://github.com/Usbac/wolff/wiki/Language)

* [**Routing system**](https://github.com/Usbac/wolff/wiki/Routes)

* [**Standard library**](https://github.com/Usbac/wolff/wiki/Standard-library)

* [**Template engine**](https://github.com/Usbac/wolff/wiki/Template)

And **much** more...

## Requirements

* PHP version 7.0 or higher

* Composer

## Install

[Composer](https://getcomposer.org/) is required for installing Wolff, once you got it...

Run the following command in the folder where you want Wolff to be installed:

```
composer create-project usbac/wolff
```

This will download the whole project with everything ready to run.

You can see more information about the installation process in the [Wiki - install](https://github.com/Usbac/Wolff/wiki/Installation) page.

_You can also download the last bundle [here](https://github.com/Usbac/wolff/releases/download/v3.1.0/wolff-bundle.zip)._

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

## Testing

[PHPUnit](https://phpunit.de) is required for the tests, once you got it.

Run the following command with high privileges (sudo) in your wolff project folder:

```
vendor/bin/phpunit
```

_Running the command with high privileges is required since some files will be created during the testing process._

## Documentation

First time using it? Read the [Documentation](https://getwolff.com/doc/3.x/home).

## Contributing

Any contribution or support to this project in the form of a pull request or message will be highly appreciated. ❤️

You can read more about the contribution process [right here](CONTRIBUTING.md). Don't be shy :)

## License

Wolff is open-source software licensed under the [MIT license](https://github.com/Usbac/Wolff/blob/master/LICENSE).
