`Wolff\Utils\Pagination`

The pagination utility makes incredibly easy the process of creating a web page with pagination.

Its constructor looks like this:

`__construct([int $total[, int $per_page[, int $page[, int $side_pages_n[, string $url_format]]]]])`

And its main method `get` returns an array with the elements that will be shown in a pagination view.

### Example

```php
$pagination = new Pagination(
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

Keep in mind that the methods can be chained for syntactic sugar, so the following code is valid:

```php
$pagination->setTotal(100)
           ->setPageSize(10)
           ->setPage(5)
           ->setSidePages()
           ->setUrl('blog/posts/{page}')
           ->showEnds();
```

### Set total

`setTotal(int $total): Wolff\Utils\Pagination`

Sets the total number of elements.

```php
$pagination->setTotal(100);
```

### Set page size

`setPageSize(int $per_page): Wolff\Utils\Pagination`

Set the total number of elements per page.

```php
$pagination->setPageSize(10);
```

### Set current page

`setPage([int $page]): Wolff\Utils\Pagination`

Set the current page.

```php
$pagination->setPage(5);
```

By default it's zero `0`.

### Set number of side pages

`setSidePages([int $side_pages_n]): Wolff\Utils\Pagination`

Set the number of pages that will be at the left and right side of the current page.

```php
$pagination->setSidePages(3);
```

Giving the example shown above, if the current page is 5, the pagination utility will show the pages `2 3 4 5 6 7 8`.

```php
$pagination->setSidePages(1);
```

Giving the example shown above, if the current page is 5, the pagination utility will show the pages `4 5 6`.

By default it's five `5`.

### Set pages url

`setUrl(string $url_format): Wolff\Utils\Pagination`

Set the url format used for the views.

The url uses this `{page}` placeholder, which will be replaced by the page number.

```php
$pagination->setUrl('blog/posts/{page}');
```

Giving the example shown above, the page 3 should have the following value in its url: `blog/posts/3`.

### Set show ends

`showEnds([bool $show_ends]): Wolff\Utils\Pagination`

Set on or off the display of the first and last page.

```php
$pagination->showEnds(true);
```

By default the value is `true`.
