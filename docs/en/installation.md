## Prerequisites

Wolff works with composer. In order to use it for the first time you must have Composer installed in your machine.

You can see how to install Composer in the [official page](https://getcomposer.org/doc/00-intro.md).

## Starting

Once you have installed Composer, there are two ways to install Wolff:

### 1.- Clone/Download

Clone or download the Wolff repository, then open your terminal, move to your Wolff folder and run the following command:

`composer install`

This will download the rest of the required files and then you will be done :)

### 2.- Packagist

The other way to install Wolff is through Packagist: open your terminal, move to the folder where you want Wolff to be installed and run the following command:

`composer create-project usbac/Wolff`

This will download the whole project with everything required to run.

_If you have any problem during the installation, just remember to clear the Composer cache using: `composer clear-cache`._

## Welcome page

Once you're done with the Wolff installation, you should have a `wolff` folder, move that folder to your server root (which commonly is `var/www/html` or `C:\xampp\htdocs`).

After that, start your localhost server and go to the link: `localhost/wolff`. You should be able to see the Wolff welcome page :).

### PHP build in server

Another alternative is using the PHP build-in web server, move to your `wolff` folder and run:

`sudo php -S localhost:80 -t public`

Now accessing `localhost` should show you the Wolff welcome page.
