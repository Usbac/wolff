## Composer

Wolff works with composer. In order to use it for the first time you must have Composer installed in your machine.

You can see how to install Composer in the [official page](https://getcomposer.org/doc/00-intro.md).

## Server

There are two htaccess files in wolff, one in the root folder that redirects to the public folder. Which must have the following code:

```
Options -Indexes

RewriteEngine on
RewriteRule ^$ public/ [L]
RewriteRule ^((?!public/).*)$ public/$1 [L,NC]
```

And one in the public folder that calls the `index.php` file in the same folder if the request isn't for a file or folder.

```
Options -Indexes

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## Wolff

Once you have downloaded Composer, there are two ways to install Wolff:

### 1.- Clone/Download

Clone or download this repository, after that, open your terminal and move to the folder where the Wolff content is and run the following command:

`composer install`

This will download the rest of the required files and then you will be done :)

### 2.- Packagist

The other way to install Wolff is through Packagist: open your terminal, move to the folder where you want Wolff to be installed and run the following command:

`composer create-project usbac/Wolff`

This will download the whole project with everything required to run.

If you have any problem during the installation, just remember to clear the Composer cache using: `composer clear-cache`

## Starting

Once you're done with the Wolff installation, you should have a `wolff` folder, move that folder to your server root (which commonly is `var/www/html` or `C:\xampp\htdocs`).

After that, start your localhost server and go to the link: `localhost/wolff`. You should be able to see the Wolff welcome page :).

Another alternative is using the PHP build-in web server, move to your `wolff/public` folder and run the following command in your terminal: `php -S localhost:8000`.