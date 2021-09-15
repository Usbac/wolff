`Wolff\Core\Cache`

The views use by default a cache system.

When loading a view, a file titled `{fileDirectory}.tmp` will be created in the cache folder if it doesn't exists already.

So the view will be loader from that file and any change made to the original file will not be displayed until the cache file expires or is deleted manually.

You can perfectly refresh the cache by deleting the cache folder content or the folder itself.

If you want to force a view to avoid the use of the cache system, you can pass a `false` value to the `render` method of the `Wolff\Core\View` class as the third parameter.

```php
$view->render('home', $data, false);
```

That will force that view to not use the cache system. Keep in mind that the loading time could increase due to this.

The default life time for a cache file is One week.

## Methods

### Is enabled

`isEnabled(): bool`

Returns `true` if the cache system is enabled, `false` otherwise.

```php
Cache::isEnabled();
```

### Set status

`setStatus([bool $enabled]): void`

Sets the status of the cache system. `true` to enable it, `false` to disable it.

```php
Cache::setStatus(true);
```

### Get cache content

`get(string $dir): string`

Returns the content of the specified cache file.

```php
Cache::get('home');
```

That will return the content of the home cache file (`home.tmp`).

### Create file

`set(string $dir, string $content): string`

Creates a cache file, only if it does not exists.

```php
$file_content = '<h2>Hello</h2>';
Cache::set('home', $file_content);
```

The first parameter is the desired directory for the cache file, the second is the content that will be written into that file.

This method returns the path of the created cache file.

### Create folder

`mkdir(): bool`

Makes the cache folder if it doesn't already exists.

This method returns `true` if the cache folder exists or has been created, `false` otherwise.

```php
Cache::mkdir();
```

### Has

`has(string $dir): bool`

Returns `true` if the given cache key exists, `false` otherwise.

```php
Cache::has('home');
```

That will return `true` if the home cache file exists, `false` otherwise.

### Get filename

`getFilename(string $dir): string`

Returns the absolute path of the given cache file.

```php
Cache::getFilename('home');
```

That example should return something like `/var/www/html/wolff/cache/home.tmp`.

### Delete

`delete(string $dir): bool`

Deletes the specified cache file. 

This method returns `true` if the file has been successfully deleted, `false` otherwise.

```php
Cache::delete('home');
```

That will delete the home cache file (`cache/home.tmp`).

### Clear

`clear([int $seconds]): void`

Deletes the cache files.

```php
Cache::clear();
```

That will delete all the cache files.

This method can take an optional parameter which is the minimum time (in seconds) that a file needs to have since its last creation/modification to be deleted.

```php
Cache::clear(60);
```

That will delete any cache file that has been created/modified more than 60 seconds ago.
