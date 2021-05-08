`Wolff\Core\DB`

You can run queries using the Database class of Wolff. It's basically an abstraction layer builded on top of PDO, meaning that it simplifies the process of running queries and is also safe and reliable.

_Keep in mind that the PHP PDO extension must be installed and enabled for the database to work._

## Configuration

The database constructor looks like this.

`__construct([array $data[, array $options ]])`

It takes two parameters, an array with the database credentials, and an array which will be used as the options for the `PDO` instance that the utility uses internally.

If no data array is passed, it will use the credentials defined in the `db` key of the `system/config.php` file array.

```php
$db = new Wolff\Core\DB();
```

```php
$db = new Wolff\Core\DB([
    'dsn'      => 'mysql:host=localhost;dbname=testdb'
    'username' => 'root',
    'password' => '12345',
]);
```

Both examples are right.

## Running queries

`query(string $sql[, ...$args]): \Wolff\Core\Query`

The `query` method returns a `Wolff\Core\Query` object.

```php
$db->query('SELECT * FROM table');
```

You can prepare a query passing multiple parameters after the first one.

```php
$db->query('SELECT * FROM user WHERE id = ?', $id);
```

```php
$db->query('SELECT * FROM user WHERE name = ? and email = ?', $name, $email);
```

## Query

The `Wolff\Core\Query` object returned by the `query` method has the following methods.

**Note**: This retrieves the full query from the database, and processes the results in PHP. To have the databse perform the operation, pass it in a database-appropriate syntax as part of the query.

### Get

`get(): array`

Returns the query result as an associative array.

```php
$db->query('SELECT * FROM table')->get();
```

### Get Json

`getJson(): string`

Returns the result as a JSON.

```php
$db->query('SELECT * FROM table')->getJson();
```

### Limit

`limit(int $start[, int $end]): array`

Returns the query result as an associative array sliced.

```php
$db->query('SELECT * FROM table')->limit(0, 5);
```

In that example only 5 rows will be returned.

### First

`first([string $column]): array`

Returns the first element of the query result.

```php
$db->query('SELECT * FROM table')->first();
```

You can pass a column name to the method. That will return only the specified column value of the first element.

```php
$db->query('SELECT * FROM table')->first('column');
```

### Count

`count(): int`

Returns the number of query rows.

```php
$db->query('SELECT * FROM table')->count();
```

### Pick

`pick(...$columns): array`

Returns the query result only with the specified columns.

```php
$db->query('SELECT * FROM users')->pick('name');
```

That would return something like this:
```php
Array
(
    [0] => Margaret Brown
    [1] => Thomas Andrews
    [2] => Bruce Ismay
)
```

```php
$db->query('SELECT * FROM users')->pick('name', 'age');
```

That would return something like this:
```php
Array
(
    [0] => Array
        (
            [name] => Margaret Brown
            [age] => 40
        )

    [1] => Array
        (
            [name] => Thomas Andrews
            [age] => 45
        )

)
```

### Var dump

`dump(): void`

Var dump the query result.

```php
$db->query('SELECT * FROM table')->dump();
```

### Var dump and die

`dumpd(): void`

Var dump the query result and die.

```php
$db->query('SELECT * FROM table')->dumpd();
```

### Print result

`printr(): void`

Prints the query result in a nice looking way.

```php
$db->query('SELECT * FROM table')->printr();
```

### Print result and die

`printrd(): void`

Prints the query result in a nice looking way and die.

```php
$db->query('SELECT * FROM table')->printrd();
```

## General methods

### Get Pdo

`getPdo(): ?\PDO`

Returns the PDO object.

```php
$db->getPdo();
```

### Get last id

`getLastId(): ?string`

Returns the last inserted ID in the database.

```php
$db->getLastId();
```

### Get last statement

`getLastStmt(): \PDOStatement`

Returns the last PDO statement executed.

```php
$db->getLastStmt();
```

### Get last query

`getLastSql(): mixed`

Returns the last query executed.

```php
$db->getLastSql();
```

And you can get its arguments with `getLastArgs`.

```php
$db->getLastArgs();
```

Finally you can re run the last query with `runLastSql`.

```php
$db->runLastSql();
```

### Table exists

`tableExists(string $table): bool`

Returns `true` if the specified table exists, `false` otherwise.

```php
$db->tableExists('users');
```

_WARNING: This method must NOT be used with user input since it does not escapes the given arguments._

### Column exists

`columnExists(string $table, string $column): bool`

Returns `true` if the specified table and column exists, `false` otherwise.

```php
$db->columnExists('users', 'user_id');
```

The first parameter is the table where the column is, the second is the column name.

_WARNING: This method must NOT be used with user input since it does not escapes the given arguments._

### Move rows

`moveRows(string $src_table, string $dest_table[, string $conditions[, $args]]): bool`

Moves rows from the source table to the destination table.

This method returns `true` if the transaction has been made successfully, `false` otherwise.

```php
$db->moveRows('customers', 'new_customers', 'WHERE status = 1');
```

_In case of errors, the changes are completely rolled back._

_WARNING: This method must NOT be used with user input since it does not escapes the given arguments._

## Fast methods

The DB class has some fast methods you can use.

### Insert

`insert(string $table, array $data): mixed`

Inserts the given data into the specified table.

The first parameter must be the table name where the data will be inserted, the second parameter must be an associative array with data.

Take in mind that the array keys will be directly mapped to the column names.

```php
$db->insert('product', [
    'name'     => 'phone',
    'model'    => 'PHN001',
    'quantity' => 5,
]);
```

That will be the same as `INSERT INTO 'product' (name, model, quantity) VALUES ('phone', 'PHN001', '5')`.

Note that this method only allows for one row of data to be inserted per call.

### Select

`select(string $table[, string $conditions[, ...$args]]): array`  

Runs a select query in the specified table. 

This method returns the result as an associative array, and accepts dot notation.

```php
$db->select('users');
$db->select('users', 'id = ?', 1);
$db->select('users.name');
```

Equivalent to:

`SELECT * FROM users`  
`SELECT * FROM users WHERE id = 1`.  
`SELECT name FROM users`.

### Count

`count(string $table[, string $conditions[, ...$args]]): int`

Returns the number of rows in the specified table.

```php
$db->count('users');
$db->count('users', 'id = ?', 1);
```

Equivalent to:

`SELECT COUNT(*) FROM users`  
`SELECT COUNT(*) FROM users WHERE id = 1`.

### Delete

`delete(string $table[, string $conditions[, ...$args]]): bool`

Deletes the rows in the specified table.

This method returns `true` in case of success, `false` otherwise.

```php
$db->delete('users');
$db->delete('users', 'id = ?', 1);
```

Equivalent to:

`DELETE * FROM users`  
`DELETE * FROM users WHERE id = 1`.
