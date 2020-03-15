The upload class simplifies the process of uploading files to the server. 
You can instantiate a new Uploader the following way:

```php
$uploader = new \Utilities\Uploader();
```

Then you can user the following methods.

## General

Just remember to `use Utilities\Uploader`.

### Max size

You can set the maximum allowed file size (expressed in Kb):

```php
$uploader->setMaxSize(1000);
```

You can get the maximum allowed file size this way:

```php
$uploader->getMaxSize();
```

### Directory

By default every file is uploaded to the server root folder.
But you can change the default directory this way:

```php
$uploader->setDirectory(getPublic('images'));
```

This will upload any file to the public/images folder.

You can get the choosen directory this way:

```php
$uploader->getDirectory();
```

### Last file

To get information about the last file uploaded, you can use the `getLastFile` method:

```php
$uploader->getLastFile();
```

That will return an array with information about the file name, file type, file size, directory, uploader IP, upload date and any upload error.


## Format match

You can validate a file format using the `matchFormat` method.

It will return true if the file matches the formats especified:

```php
$matches = $uploader->matchFormat('examplefile', 'jpg, png, gif'));
```

You can pass a string separated by commas or an array like this:

```php
$formats = array('jpg', 'png', 'gif');
$matches = $uploader->matchFormat('examplefile', $formats));
```

## Uploading files

First create a form in your view like this:

```html
<form action="yourpage/upload" method="post" enctype="multipart/form-data">
     <input type="file" name="examplefile"/> 
     <input type="submit" value="upload"/> 
</form> 
```

Then in your controller create a function named `upload` with the following code:

```php
//Optional configuration
$uploader->setMaxSize(1000)->setDirectory('');

//File upload
$uploader->file('examplefile');
```

If the examplefile size isn't greater than 1000 Kb it will be uploaded to the public directory.
You are done!