`Wolff\Utils\Validation`

The Wolff Validation class makes incredibly easy the process of validating incoming data.

## Usage

The validation utility takes a main array which will be used to test against another array containing the conditions that the main array must match.

### Keys

The following keys can be used in the elements of the fields array.

* **min_len**: The minimum length of the variable.

* **max_len**: The maximum length of the variable.

* **min_val**: The minimum value of the variable (treating it as a number).

* **max_val**: The maximum value of the variable (treating it as a number).

* **regex**: The regular expression that the variable must met.

* **type**: The type of the variable, the available options are `email`, `alphanumeric`, `alpha`, `int`, `float`, `bool`.

### Example

```php
$data = [
    'name' => 'Thomas Andrews',
    'age'  => 39,
];

$fields = [
    'name' => [
        'minlen' => 10,
    ],
    'age' => [
        'minval' => 18,
    ],
];

$validation = new Validation();
$validation->setData($data);
$validation->setFields($fields);
```

Running the following code should return `true`:

```php
$validation->isValid();
```

The `fields` array has multiple elements, each one is an array containing the conditions that the key with the same name in the main array must meet. In this case the `name` field must have a minimum length of 10 characters and the `age` must have a minimum value of 18. Only if all of those conditions are met the `isValid` method will return true.

### Example 2

A more expressive example

```php
$data = [
    'name' => 'Thomas Andrews',
    'age'  => 39,
];

$fields = [
    'name' => [
        'minlen' => 10,
        'type'   => 'alpha',
    ],
    'age' => [
        'minval' => 18,
        'type'   => 'int',
    ]
];

$validation = new Validation();
$validation->setData($data);
$validation->setFields($fields);
```

In that case the `isValid` method will return `true` only if the name has a minimum length of 10 characters and contains only letters, and the age has a minimum value of 18 and is of type int, meaning that a string containing: `18` isn't valid.

## General Methods

The `setData` and `setFields` methods can be chained.

```php
$validation->setData($data)->setFields($fields);
```

### Set data

`setData(array $arr): \Wolff\Utils\Validation`

Sets the array which will be used to validate.

```php
$validation->setData($array);
```

### Set fields

`setFields(array $fields): \Wolff\Utils\Validation`

Sets the array which contains the fields definitions.

```php
$validation->setFields($fields);
```

### Get invalid values

`getInvalidValues(): array`

Returns an array with all the invalid values.

```php
$validation->getInvalidValues();
```

If the `age` key doesn't meet the minimum value condition and the `name` key doesn't meet the minimum length, it should return this:

```php
Array
(
    [name] => Array
        (
            [0] => minlen
        )

    [age] => Array
        (
            [0] => minval
        )

)
```

An associative array where every element is an array of conditions that the element didn't meet.

### Is valid

`isValid(): bool`

Returns `true` if the array matches with all the conditions, `false` otherwise.

```php
$validation->isValid();
```

### Check

`check($fields, $data): bool`

This is just syntactic sugar for the call to `setFields`, `setData` and `isValid` methods in that order.

The first parameter is the fields array, the second is the data to validate array. 

This method returns `true` if the given data array meets the conditions in the given fields array, or `false` if it doesn't.

```php
$validation->check($fields, $data);
```
