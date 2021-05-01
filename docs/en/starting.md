Supposing that you have installed Wolff, it's time to create your first own page.

That's why there are some useful examples below.

_Warning: These examples only show how to use the basic components of Wolff, they are NOT supposed to be use as a reference for production code._

## Simple insertion

This example shows a page (available at `localhost/form`) with a simple form that inserts data into the database. Here we will use the controller, language, database and view utilites.

_The database class will use the credentials available in `system/config.php` file._

app/languages/english/form.php:
```php
<?php

return [
    'name'       => 'Name',
    'email'      => 'Email address',
    'password'   => 'Password',
    'btn_submit' => 'Submit'
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
        $data = $req->body();
        $db = new DB();
        $db->query("INSERT INTO user (name, email, password)
            VALUES (:name, :email, :password)", $data);

        echo 'Done';
    }
}
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

## Safer insertion

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
            'type'   => 'alpha'
        ],
        'email' => [
            'type' => 'email'
        ],
        'password' => [
            'minlen' => 8
        ]
    ]);

    if (!$validation->isValid()) {
        echo 'Invalid data';
        return;
    }

    $db = new DB();
    $db->query("INSERT INTO user (name, email, password)
        VALUES (:name, :email, :password)", $req->body());
    echo 'Done';
}
```
