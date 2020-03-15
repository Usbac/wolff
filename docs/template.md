The template system of Wolff allows you to write cleaner PHP code in your views, avoiding things like the PHP tags.

It only works in the views and is completely optional, so you can write normal PHP code if you want to.

The template system can be completely disabled if the `template_on` key in the `system/config.php` file is equal to false.

## Variables

### Load 

When loading a view from a controller, you can pass as parameter an associative array with data in it:

```php
$data['message'] = 'Hello world';
View::render('page', $data);
```

### Display

 Then in the view you can print the variables that are in that array using the brackets tag, this way:
```php
{{ $message }}
```

This will print the 'message' key in the data array, keep in mind that the content will be automatically escaped for your safety. 

If you don't want your data to be escaped use the following tags:

```php
{! $message !}
```

That is the equivalent to:

```php
<?php echo $data['message']; ?>
```

All the variables in the data array are accessible from the view without needing to refer to the data array. This `$variable` is the equivalent to this `$data['variable']`.

## Comments

The template system have an advantage over the common comments and that is that the comments of the template system aren't included in the final HTML returned to the user.

```html
{# This is a simple comment #}
```

You can do multiline comments too.

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

## Conditionals

To write conditional statements, put your variable/condition before an interrogation symbol, all of this inside brackets. 

```php
{ $foo? }
    // code
{?}
```

That is the equivalent to this:

```php
<?php if ($foo): ?>
    // code
<?php endif; ?>
```

To write else if:

```php
{ $foo? }
    // code
{ else $foo2 }
    // more code
{?}
```

## Loops

You can write traditional for loops in a short way:

```php
{ for i in (0, 10) }
    {{$i}}
{endfor}
```

The same but using variables:

```php
{ for i in (0, length|$text) }
    {{$i}}
{endfor}
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

| Template    | PHP Function     | Description                                    |
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

You may want to escape the template tags, in that situation only prefix your text/tag with an `~` symbol, like this:

```
~{{ $message }}
```

That will leave this in the HTML returned to the client:

```
{{ $message }}
```

## Include

Instead of using the php include function. You can include other views using the `load` method.

Example:

```html
<div>
    @load(header)
</div>
```

That will put all the `header.wlf` view content inside the div tags.

## Extending the template

You can extend the template engine and make your own tags or rules using regular expressions and the `custom` function.

The custom content must be defined in the `system/Definitions/Templates.php` file.

The custom function takes a closure, which must take a parameter that is suposed to be the view content and finally it must return it.

If you add the following code to the `system/Templates.php` file:

```php
Template::custom(function ($content) {
    return preg_replace('/\!\!(.*?)\!\!/', '<?php echo "message: $1" ?>', $content);
});
```

Now the following tags in the views `!! $str !!` will be replaced by `<?php echo "message: $str" ?>`.