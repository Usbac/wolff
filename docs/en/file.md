`Wolff\Core\Http\File`

The request object of Wolff has an array of uploaded files, each one of the elements in that array is an instance of `Wolff\Core\Http\File`.

The request object and the file objects have some methods that can simplify the process of uploading files to the server.

## Setting Options

`fileOptions(array $arr)`

With the `fileOptions` method you can specify the options for uploading files, passing an associative array as parameter.

The array can have the following keys:

* **dir**: The directory where the files will be uploaded (relative to the project root folder).

* **extensions**: A comma separated string with the allowed file extensions.

* **max_size**: The maximum file size allowed (in KB).

* **override**: `true` for overriding files with the same path in the upload process, `false` for not overriding them.

```php
$options = [
    'dir'        => 'public',
    'extensions' => 'jpg, png, bmp',
    'max_size'   => 1024,
    'override'   => true
];

$request->fileOptions($options);
```

## File methods

### Get

`get(string $key)`

Returns the value of the specified key.

```php
$request->file('profile_image')->get('size');
```

That would be equivalent to `$_FILES['profile_image']['size']`.

### Upload

`upload([string $name])`

Uploads the file and stores it in the server with the given name.

This method returns `true` if the file has been successfully uploaded, `false` otherwise.

_If no name is provided, the original file name will be used instead._

```php
$request->file('profile_image')->upload('user_image.png');
```

## Example

Let's create a simple file upload example.

First create the form.

app/views/file_form.wlf
```html
<form action="{{ url('upload') }}" method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <button type="submit">Upload!</button>
</form>
```

Then create both routes, one that will display the form and another that will take care of the file upload.

system/web.php

```php
// Form view
Route::get('file', function ($request) {
    Wolff\Core\View::render('file_form');
});

// Upload
Route::post('upload', function ($request) {
    $request->fileOptions([
        'dir'        => 'files',
        'extensions' => 'jpg,png',
        'max_size'   => 2048,
        'override'   => false
    ]);

    if ($request->file('image')->upload()) {
        echo 'File has been successfully uploaded';
    } else {
        echo 'An error has occurred';
    }
});
```

So, if the client uploads a file that complies with:

* A `jpg` or `png` extension.
* No bigger than 2048KB.
* Its path is not taken already in the server

The message `File has been successfully uploaded` will appear, otherwise the message `An error has occurred` will appear.
