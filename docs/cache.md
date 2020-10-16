`Wolff\Core\Cache`

The views use by default a cache system.

When loading a view, a file titled `{fileDirectory}.tmp` will be created in the cache folder if it doesn't exists already.

So the view will be loader from that file and any change made to the original file will not be displayed until the cache file expires or is deleted manually.

You can perfectly refresh the cache deleting the cache folder content or the folder itself.

If you want to force a view to don't use the cache system, you can pass a `false` value to the `render` method of the `Wolff\Core\View` class as the third parameter.

```php
View::render('home', $data, false);
```

That will force that view to don't use the cache system, keep in mind that the loading time could increase due to this.

The default life time for a cache file is One week.

## Methods

### Is enabled

`isEnabled()`

Returns `true` if the cache system is enabled, `false` otherwise.

```php
Cache::isEnabled();
```

### Get cache content

`get(string $dir)`

Returns the content of the specified cache file.

```php
Cache::get('home');
```

That will return the content of the home cache file (`tmp_home.php`).

### Create file

`set(string $dir, string $content)`

Creates a cache file.

```php
$file_content = '<h2>Hello</h2>';
Cache::set('home', $file_content);
```

The first parameter is the desired directory for the cache file, the second is the content that will be written into that file.

This method returns the path of the created cache file.

### Create folder

`mkdir()`

Makes the cache folder if it doesn't already exists.

```php
Cache::mkdir();
```

### Has

`has(string $dir)`

Returns `true` if the given cache key exists, `false` otherwise.

```php
Cache::has('home');
```

That will return `true` if the home cache file exists, `false` otherwise.

### Delete

`delete(string $dir)`

Deletes the specified cache file.

```php
Cache::delete('home');
```

That will delete the home cache file (`cache/tmp_home.php`).

### Clear

`clear`

Deletes all the cache file.

```php
Cache::clear();
```

That will delete all the cache files.

