`Wolff\Core\View`

The Wolff view class is the one responsible for rendering and managing views.

## General methods

_Keep in mind that the below methods can take files with a `php` or `html` extension if you specify it, like in this example: `sub/home.html` or `blog/page.php`._

### Render

`render(string $dir[, array $data[, bool $cache]])`

Renders a view content.

The first parameter must be the view name, the second the associative array with the content that will be used in the view, the third and optional parameter is to use or not the cache file/system.

```php
View::render('sub/home', $data);
```

That will basically render the content of the `app/views/sub/home.wlf` file using the template system.

### Get source file

`getSource(string $dir)`

Returns a view content.

The parameter must be the view name.

```php
View::getSource('sub/home');
```

That will return the content of the `app/views/sub/home.wlf` file.

### Get render

`getRender(string $dir[, array $data[, bool $cache]])`

Returns a view content rendered.

The first parameter must be the view name, the second the associative array with the content that will be used in the view, the third and optional parameter is to use or not the cache file/system.

```php
View::getRender('sub/home', $data);
```

That will return the rendered content of the `sub/home` view using the `$data` array.

This will do the same as above but will ignore the cache file:

```php
View::getRender('sub/home', $data, false);
```

Take in mind that it can increase the loading time.

### Get path

`getPath(string $path)`

Returns the file path of the given view.

```php
View::getPath('sub/home');
```

By default that will return `app/views/sub/home.wlf`

### Exists

`exists(string $dir)`

Returns `true` if the given view file exists, `false` otherwise.

```php
View::exists('home');
```

That will return `true` only if the `app/views/home.wlf` file exists, `false` otherwise.
