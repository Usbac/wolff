The pagination utility makes incredibly easy the process of creating a page with pagination.

## Usability

Its main method `get` returns an array with the elements that will be shown in a pagination view.

### Example

```php
$pagination = new \Utilities\Pagination(
    50, //Total of elements
    10, //Elements per page
    3, //Current page
    4, //Number of side pages
    url('blog/{page}') //Url format
);
```

Giving the previous code, the following code `$pagination->get()` should return the following:

```php
Array
(
    [0] => Array
        (
            [index] => 1
            [current_page] => 
            [url] => http://localhost/wolff/blog/1
        )

    [1] => Array
        (
            [index] => 2
            [current_page] => 
            [url] => http://localhost/wolff/blog/2
        )

    [2] => Array
        (
            [index] => 3
            [current_page] => 1
            [url] => http://localhost/wolff/blog/3
        )

    [3] => Array
        (
            [index] => 4
            [current_page] => 
            [url] => http://localhost/wolff/blog/4
        )

    [4] => Array
        (
            [index] => 5
            [current_page] =>
            [url] => http://localhost/wolff/blog/5
        )

)

```

That array now can be printed in the view like this:

Controller:
```php
$data['pages'] = $pagination->get();
View::render('home', $data);
```

View:
```html
    {% foreach ($pages as $page): %}
        <a href="{{ $page['url'] }}">{{ $page['index'] }}</a>
    {% endforeach %}
```

### Keys

The pagination utility has the following options:

* **total**: Total of elements for the pagination.

* **per_page**: Total of elements that display per page.

* **page**: The current page.

* **side_pages_n**: The number of pages that will be shown at the left and right of the current page.

* **url_format**: The format of the url, basically will be the link to use for every page.

* **show_ends**: Display or not the first and last page.


## General methods

In the method examples a `pagination` variable will be used which is an instance of `\Utilities\Pagination`.

Keep in mind that the set methods can be chained for syntactic sugar, so the following code is valid:

```php
$pagination->setTotal(100)
           ->setPageSize(10)
           ->setPage(5)
           ->setSidePages()
           ->setUrl('blog/posts/{page}')
           ->showEnds();
```

### Set total

Set the total number of elements.

```php
$pagination->setTotal(100);
```

### Get total

Get the total number of elements.

```php
$pagination->getTotal();
```

### Set page size 

Set the total number of elements per page.

```php
$pagination->setPageSize(10);
```

### Get page size

Get the total number of elements per page.

```php
$pagination->getPageSize();
```

### Set current page

Set the current page.

```php
$pagination->setPage(5);
```

By default it's zero (0).

### Get current page

Getting the current page.

```php
$pagination->getPage();
```

### Set number of side pages

Set the number of pages that will be at the left and right side of the current page.

```php
$pagination->setSidePages(3);
```

Giving the example shown above, if the current page is 5, the pagination utility will show the pages `2 3 4 5 6 7 8`.

```php
$pagination->setSidePages(1);
```

Giving the example shown above, if the current page is 5, the pagination utility will show the pages `4 5 6`.

By default it's five (5).

### Get number of side pages

Get the number of pages that will be at the left and right side of the current page.

```php
$pagination->getSidePages();
```

### Set pages url

Set the url format used for the views.

The url uses this `{page}` placeholder, which will be replaced by the page number.

```php
$pagination->setUrl('blog/posts/{page}');
```

Giving the example shown above, the page 3 should have the following value in its url: `blog/posts/3`.

### Get pages url

Get the url format used for the views.

```php
$pagination->getUrl();
```

### Set show ends

Set on or off the display of the first and last page.

```php
$pagination->showEnds(true);
```

By default the value is `true`.

### Get show ends

Get the status of the display of the first and last page.
True if they will be displayed, false otherwise.

```php
$pagination->getShowEnds();
```