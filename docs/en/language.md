`Wolff\Core\Language`

The language system helps you maintain a multilanguage web app in a clean and easy way.

The language system is based on multiple folders, each one representing a specific language, inside those folders there can be several php files returning an array with text in that language.

Meaning that you can request a text, and depending of the configuration or other facts, you will get the text in one language or another.

## Language files

A language file must be inside the `app/languages/{languageOfChoice}` folder and have a php extension, the language file must have an associative array to return.

Basically an `app/languages/en/home.php` file should look like this:

```php
return [
    'title'   => 'Wolff',
    'message' => 'Hello World',
    'album'   => 'Sleep Well Beast'
];
```

## Getting a language content

In your classes you can access to the content of a language file using the `get` method.

`get(string $dir[, string $language])`

```php
Language::get('home');
```

That will basically return the array declared above.

If the language file doesn't exists it will return `null`.

### Getting an especific language

If the language is set to english in the `system/config.php` file that will return the content of the `app/language/en/home.php` file.

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

If the language file or the key don't exist it will return `null`.

## Language exists

`exists(string $dir[, string $language])`

Returns `true` if the given language file exists, `false` otherwise.

```php
Language::exists('home');
```

If the system language is set to english that will return `true` if the english language of home exists, `false` otherwise.

But you can specify a language using the second parameter.

```php
Language::exists('home', 'spanish');
```
