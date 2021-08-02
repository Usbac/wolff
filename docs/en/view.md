`Wolff\Core\View`

The Wolff view class is the one responsible for rendering and managing views.

## General methods

_Keep in mind that the below methods can take files' paths with a `php` or `html` extension if you specify it, like: `sub/home.html` or `blog/page.php`. If none of those extensions is specified, the `.wlf` extension will be appended to the given path._

### Render

`render(string $dir[, array $data[, bool $cache]]): void`

Renders a view content.

The first parameter must be the view name, the second the associative array with the content that will be used in the view, the third and optional parameter is to use or not the cache file/system.

```php
View::render('sub/home', $data);
```

That will basically render the content of the `app/views/sub/home.wlf` file using the template system.

### Get source file

`getSource(string $dir): string`

Returns a view content.

The parameter must be the view name.

```php
View::getSource('sub/home');
```

That will return the content of the `app/views/sub/home.wlf` file.

### Get render

`getRender(string $dir[, array $data[, bool $cache]]): string`

Returns a view content rendered.

_The content returned by this method is what is rendered when calling the `render` method._

The first parameter must be the view name, the second the associative array with the content that will be used in the view, the third and optional parameter is to use or not the cache file/system.

```php
View::getRender('sub/home', $data);
```

That will return the rendered content of the `sub/home` view using the `$data` array.

This will do the same as above but will ignore the cache file:

```php
View::getRender('sub/home', $data, false);
```

Keep in mind that it can increase the loading time.

### Get path

`getPath(string $path): string`

Returns the file path of the given view.

```php
View::getPath('sub/home');
```

By default that will return `app/views/sub/home.wlf`

### Exists

`exists(string $dir): bool`

Returns `true` if the given view file exists, `false` otherwise.

```php
View::exists('home');
```

That will return `true` only if the `app/views/home.wlf` file exists, `false` otherwise.
