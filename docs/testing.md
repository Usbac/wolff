Wolff comes with unit testing in mind, that's why it's extremely easy to test it out of the box.

The tests use [PHPUnit](https://phpunit.de), so you must have it installed for running them.

## Running tests

Open your terminal, move to your Wolff project folder and run the following command with high privileges:

```
vendor/bin/phpunit
```

_High privileges is usually refered as `sudo` in most systems (sudo vendor/bin/phpunit)._

_Running the command with high privileges is required since some files will be created during the testing process._

### Untested elements

The following elements of Wolff are not present in the tests (due to its nature):

* Authentication
* Database
