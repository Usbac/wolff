Wolff comes with unit testing in mind, that's why it's extremely easy to test it out of the box.

The tests use [PHPUnit](https://phpunit.de), so you must have it installed for running them.

## Running tests

Open your terminal, move to your Wolff project folder and run the following command with high privileges:

```
vendor/bin/phpunit
```

_High privileges is usually refered as `sudo` in most systems (sudo vendor/bin/phpunit)._

_Running the command with high privileges is required since some files will be created and then deleted during the testing process._

### Database tests

To run the tests with the database modules (`Wolff\Core\DB` and `Wolff\Utils\Auth`), just run the command with the `db` flag.

```
vendor/bin/phpunit -db
```

_The PDO Sqlite driver (pdo\_sqlite) must be enabled for the database tests to run._

## Code coverage

The code coverage of wolff is around *eighty-fourth percent (~84%).

_\* Coverage based on the result of PHPUnit while using Xdebug as code coverage driver. Keep in mind that this number can slightly change between versions._

To see the code coverage result in the terminal by yourself, run the test command with the `coverage-text` flag of PHPUnit.

```
vendor/bin/phpunit --coverage-text
```
