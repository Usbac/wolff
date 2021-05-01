Once you're done with the Wolff installation, you should have a `wolff` folder, move the folder content to your server root (commonly is `var/www/html` or `C:\xampp\htdocs`).

Then, start your local server and go to the link: `localhost/wolff`. You should be able to see the Wolff welcome page.

### PHP build in server

Another alternative is using the PHP build-in web server, move to your `wolff` folder and run:

`sudo php -S localhost:8080 -t public`

Now accessing `localhost:8080` should show you the Wolff welcome page.

## Additional configuration

If you are in a production environment, be sure to set the correct file permissions inside your Wolff project.

The recommended permissions are 0755 for folders and 0655 for PHP source files. For your safety, PHP files should be editable by the owner and readable by a group.

## Examples

It's time to create your first own page. That's why there are some useful examples below.

_Warning: These examples are NOT supposed to be used as a reference for production code._

### Simple insertion

This expressive example shows a page (available at `localhost/form`) with a simple form that inserts data into the database. Here we will use the controller, language, database, router and view modules.

_The database class will use the credentials available in `system/config.php` file._

app/languages/en/form.php:
```php
<?php

return [
    'name'       => 'Name',
    'email'      => 'Email address',
    'password'   => 'Password',
    'btn_submit' => 'Submit',
];
```

app/controllers/form.php:
```php
<?php

namespace Controller;

use Wolff\Core\{DB, Language, View};

class Form
{
    // Form view
    public function index($req, $res)
    {
        $data['lang'] = Language::get('form');
        View::render('form', $data);
    }

    // Form submit
    public function submit($req, $res)
    {
        $db = new DB();
        $db->query("INSERT INTO user (name, email, password)
            VALUES (:name, :email, :password)", $req->body());

        echo 'Done';
    }
}
```

system/web.php
```php
<?php

use Wolff\Core\Route;

Route::get('/', [ Controller\Home::class, 'index' ]);
Route::get('form/submit', [ Controller\Home::class, 'submit' ]);
```

app/views/form.wlf:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wolff form</title>
</head>
<body>
    <form action="{{ url('form/submit') }}" method="post">
        <input type="text" name="name" placeholder="{{ $lang['name'] }}">
        <input type="text" name="email" placeholder="{{ $lang['email'] }}">
        <input type="password" name="password" placeholder="{{ $lang['password'] }}">

        <button type="submit">{{ $lang['btn_submit'] }}</button>
    </form>
</body>
</html>
```

### Safer insertion

You can replace the code of the `submit` method in the controller with the following code, which uses the Wolff validation utility.

app/controllers/form.php:
```php
// Form submit
public function submit($req, $res)
{
    $validation = new Validation;
    $validation->setData($req->body());
    $validation->setFields([
        'name' => [
            'minlen' => 4,
            'type'   => 'alpha',
        ],
        'email' => [
            'type' => 'email',
        ],
        'password' => [
            'minlen' => 8,
        ]
    ]);

    if ($validation->isValid()) {
        $db = new DB();
        $db->query("INSERT INTO user (name, email, password)
            VALUES (:name, :email, :password)", $req->body());
        echo 'Done';
    } else {
        echo 'Invalid data';
    }
}
```
