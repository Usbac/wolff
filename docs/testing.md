Wolff comes with unit testing in mind, that's why it's extremely easy to test it out of the box.

## Running tests

First you must have [PHPUnit](https://phpunit.de) installed, once you have it.

Open your terminal, move to your Wolff project folder and run the following command with high privileges:

```
vendor/bin/phpunit
```

_High privileges is usually refered as `sudo` in most systems (sudo vendor/bin/phpunit)._

_Running the command with high privileges is required since some files will be created during the testing process._

## Test coverage

Yes, assigning a specific number or percentage to a test coverage is quite ambiguous. So take the following number with a grain of salt.

**Wolff tests cover more or less the ~70% of all the framework.**

### Unstested elements

The following elements of Wolff are not present in the tests (maybe due to its nature):

* Authentication
* Database
* Response

### Not fully covered elements

The following elements are available in the tests but are not fully covered:

* Session
* Maintenance
* Request
* Template

_Keep in mind that if a element is not present in the lists showed above, it means that it's present and fully covered in the tests._
