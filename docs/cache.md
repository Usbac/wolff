The views use by default a cache system.

When loading a view, a file titled `tmp_{fileDirectory}.php` will be created in the cache folder if it doesn't exists already.

So the view will be loader from that file and any change made to the original file will not be displayed until the cache file expires or is deleted manually.

You can perfectly refresh the cache deleting the cache folder content or the folder itself.

If you want to force a view to don't use the cache system, you can pass a false value to the render method of the `Core\View` class as the third parameter.

```php
View::render('home', $data, false);
```

That will force that view to don't use the cache system, keep in mind that the loading time could increase due to this.

The default life time for a cache file is One week.

## Methods

Just remember to `use Core\Cache`.

### Is enabled

You can check whetever or not the cache system is enabled with the `isEnabled` method.

```php
Cache::isEnabled();
```

### Get cache content

You can get the content of a cache file by using the `getContent` method.

```php
Cache::getContent('home');
```

That will return the content of the home cache file (`tmp_home.php`).

### Create file

You can manually create a cache file using the `set` method.

```php
$file_content = '<h2>Hello</h2>';
Cache::set('home', $file_content);
```

The first parameter is the desired directory for the cache file, the second is the content that will be written into that file.

This method returns the path of the created cache file.

### Create folder

You can manually create the cache folder if it doesn't exists, using the `mkdir` method.

```php
Cache::mkdir();
```

### Has

If you want to know if a cache file already exists for a view you can use the `has` method.

```php
Cache::has('home');
```

That will return true if the home cache file exists, false otherwise.

### Delete

You can delete a cache file using the `delete` method.

```php
Cache::delete('home');
```

That will delete the home cache file.

### Clear

You can delete all the cache using the `clear` method.

```php
Cache::clear();
```

That will delete all the cache files.

### Expired

You can see if a cache has expired using the `expired` method.

```php
Cache::expired('home');
```

This will return true if the home cache file has expired.

### Get Filename

Returns the cache format name applied over the specified file name.

```php
Cache::getFilename('home');
```

Will return `tmp_home.php`

### Get path

Returns the complete path of the specified cache file.

```php
Cache::getPath('home');
```

As an example, it will return `wolff/cache/tmp_home.php`