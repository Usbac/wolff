The language system helps you maintain a multilanguage web app in a clean and easy way.

## Language files

A language file must be in the `app/languages/{languageOfChoice}` folder and have a php extension, the language file must have an associative array to return. Basically a `home.php` language should look like this:

```php
return [
    'title'   => 'Wolff',
    'message' => 'Hello World',
    'album'   => 'Sleep Well Beast'
];
```

## Loading a language

Just remember to `use Core\Language`.

In your classes you can access to the content of a language file using the `get` method

```php
Language::get('home');
```

That will basically return the array declared above.

### Language to use

If the language is set to english in the `system/config.php` file that will return the content of the `app/language/english/home.php` file.

Instead, if the language is set to spanish that will return the content of the `app/language/spanish/home.php` file.

But you can specify the language to get passing it as the second argument.

```php
Language::get('home', 'spanish');
```

That will return the spanish language of home, regardless of the configuration.

### Getting only one key

You can even get only one key of the language. Using a dot notation.

```php
Language::get('home.message');
```

That will return the message key value of the home language array.

## General methods

### Get path

Returns the file path of the given language.

```php
Language::getPath('sub/home');
```

If the system language is set to english that will return `app/languages/english/home.php`. 

But you can specify any language using the second parameter.

```php
Language::getPath('sub/home', 'spanish');
```

### Exists

Returns true if the given language file exists, false otherwise.

```php
Language::exists('home');
```

If the system language is set to english that will return true if the english language of home exists, false otherwise. 

But you can specify any language using the second parameter.

```php
Language::exists('home', 'spanish');
```