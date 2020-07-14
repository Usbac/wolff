`Wolff\Core\Template`

The template system of Wolff allows you to write cleaner and safer PHP code in your views by avoiding things like the PHP tags and automatically escaping all the output.

It only works in the views and is completely optional, so you can write normal PHP code if you want to.

The template system can be completely disabled if the `template_on` key in the `system/config.php` file is equal to `false`.

Views can be rendered anywhere inside your Wolff project.

## Sending data to views

When loading a view from a controller, you can pass as parameter an associative array with data in it:

```php
$data['message'] = 'Hello world';
View::render('page', $data);
```

## Print

 Then in the view you can print the variables that are in that array using the brackets tag, this way:

```php
{{ $message }}
```

This will print the 'message' key in the data array.

_Keep in mind that the content will be automatically escaped for your safety._

### Print raw

If you don't want your data to be escaped use the following tags:

```php
{! $message !}
```

That is the equivalent to:

```php
<?php echo $data['message']; ?>
```

All the variables in the data array are accessible from the view without needing to refer to the given array. This `$variable` is the equivalent to this `$data['variable']`.

## Comments

The template comments have an advantage over the common comments and is that the comments of the template system aren't included in the final HTML returned to the user.

```html
{# This is a simple comment #}
```

You can write multiline comments too:

```html
{# This is a
multiline
comment #}
```

## Tags

In Wolff you can use the replacement of the old php tags, which are `{%` for `<?php` and `%}` for `?>`.

Instead of this:

```php
<?php foreach ($array as $key => $value): ?>
    //code
<?php endfor ?>
```

You can do this:

```php
{% foreach ($array as $key => $value): %}
    // code
{% endfor %}
```

## Loops

You can write traditional for loops in a short way:

```php
{% for $i in (0, 10) %}
    {{$i}}
{% endfor %}
```

That should print `012345678910`.

The same but using variables:

```php
{% for $i in (0, length|$text) %}
    {{$i}}
{% endfor %}
```

## Functions

The template system of Wolff has some abbreviated functions to make the code cleaner.

To use a function only write its' name followed by a vertical bar and then the variable.
Like this:

```php
{{ upper|$title }}
```

In this case, that will print the `$title` variable in uppercase.

```php
{{ repeat(3)|$title }}
```

In this case, that will print the `$title` variable three times.

### List

This are the available functions and their PHP equivalent:

| Template    | PHP Equivalent   | Description                                    |
| ------------|----------------- |------------------------------------------------|
| e           | htmlspecialchars<br>strip_tags | perfect for avoiding xss         |
| upper       | strtoupper       | all text to uppercase                          |
| lower       | strtolower       | all text to lowercase                          |
| upperf      | ucfirst          | first letter to uppercase                      |
| length      | strlen           | string length                                  |
| count       | count            | array length                                   |
| title       | ucwords          | all first word letters to uppercase            |
| md5         | md5              | md5 hash value                                 |
| countwords  | str_word_count   | number of words                                |
| trim        | trim             | whitespace stripped from the beginning and end |
| nl2br       | nl2br            | insert HTML line breaks before newlines        |
| join(var)   | implode          | Join an array by a text (var)                  |
| repeat(var) | str_repeat       | repeat a string fixed number of times (var)    |

## CSRF

The template engine has a tag which can be used to avoid CSRF.

```
@csrf
```

Internally it creates a `__token` cookie if it doesn't exists and replaces the CSRF tag with a hidden input which value is the same of the cookie.

_Keep in mind that the cookie has a live time of one hour and is http only._

```html
<form action="theurl" method="post">
    @csrf
    <input type="text" name="username"/>
    <button type="submit">Send</button>
</form>
```

Then you can use the `validateCsrf` function of the standard library to verify the incoming form.

```php
if (validateCsrf()) {
    echo 'Safe, continue';
    // Code
} else {
    echo 'You shall not pass';
}
```

## Import

Instead of using the html script and link tags for importing external files, you can include them using the template tags.

```
{% style="styles.css" %}
```

Equivalent to: `<link rel="stylesheet" type="text/css" href="styles.css"/>`

```
{% script="scripts.js" %}
```

Equivalent to: `<script type="text/javascript" src="scripts.js"></script>`

```
{% icon="img.svg" %}
```

Equivalent to: `<link rel="icon" href="img.svg">`


## Escape

For escaping the template tags, only prefix your text/tag with an hypen `~` symbol, like this:

```
~{{ $message }}
```

That will leave this in the HTML returned to the client:

```
{{ $message }}
```

## Include

Instead of using the php include function. You can include other views by using the `include` method.

Example:

```html
<div>
    @include('header')
</div>
```

That will put all the `header.wlf` view content inside the div tags.

The included view has access to all the variables in the current scope.

## View inheritance

View inheritance is a great feature to reduce repeated code.

_Wolff supports view inheritance, but not multiple view inheritance._

### Extending the view

The parent view can have multiple blocks which will be used by the child views to redefine what is inside them.

A child view must extend from a parent, and for that, use the following syntax.

```html
@extends('parent_view')
```

### Print parent block

You can print the content of a parent's block anywhere in the child view with the following syntax.

```html
{[ parent block_name ]}
```

### Example

app/views/base.wlf:

```html
<!DOCTYPE html>
<head>
    {[ block head ]}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {[ endblock ]}
</head>
<body>
    {[ block body ]}
    {[ endblock ]}
</body>
</html>
```

app/views/child.wlf:

```html
@extends('base')

{[ block head ]}
    {[ parent head ]}
    <title>Tundra</title>
{[ endblock ]}

{[ block body ]}
    <div>Hello world</div>
{[ endblock ]}
```

Given the examples of above, the following code:

```php
View::render('child');
```

Will render this:
```html
<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tundra</title>
</head>
<body>
    <div>Hello world</div>
</body>
</html>
```

## Extending the template

`custom($function)`

You can extend the template engine and make your own tags or rules by using the `custom` method.

The method takes a closure, which must take a parameter that is suposed to be the view content and it must return it. What you do with the view content inside the function is up to you.

### Example

If you add the following code to the `system/web.php` file:

```php
Template::custom(function ($content) {
    return preg_replace('/\!\!(.*?)\!\!/', '<?php echo "message: $1" ?>', $content);
});
```

Now the following tags in the views `!! $str !!` will be replaced by `<?php echo "message: $str" ?>`.
