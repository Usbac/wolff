`Wolff\Core\DB`

You can run queries using the Database class of Wolff. It's basically an abstraction layer builded on top of PDO, meaning that it simplifies the process of running queries and is also safe and reliable.

## Configuration

The database constructor looks like this.

`__construct([array $data[, array $options ]])`

It takes two parameters, an array with the database credentials, and an array which will be used as the options for the `PDO` instance that the utility uses internally.

If no data array is passed, it will use the credentials defined in the `system/config.php` file.

```php
$db = new Wolff\Core\DB();
```

```php
$credentials = [
    'dbms'   => 'mysql',
    'server' => 'localhost',
    'name'   => 'wolff',
    'username' => 'root',
    'password' => '12345'
];

$db = new Wolff\Core\DB($credentials);
```

Both examples are right.

## Running queries

`query(string $sql[, ...$args])`

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

The `Wolff\Core\Query` object returned by the `query` method has the following method.

### Get

`get()`

Returns the query result as an associative array.

```php
$db->query('SELECT * FROM table')->get();
```

### Get Json

`getJson()`

Returns the result as a JSON.

```php
$db->query('SELECT * FROM table')->getJson();
```

### Limit

`limit(int $start[, int $end])`

Returns the query result as an associative array sliced.

```php
$db->query('SELECT * FROM table')->limit(0, 5);
```

In that example only 5 rows will be returned.

### First

`first([string $column])`

Returns the first element of the query result.

```php
$db->query('SELECT * FROM table')->first();
```

You can pass a column name to the method. That will return only the specified column value of the first element.

```php
$db->query('SELECT * FROM table')->first('column');
```

### Count

`count()`

Returns the number of query rows.

```php
$db->query('SELECT * FROM table')->count();
```

### Pick

`pick(...$columns)`

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

`dump()`

Var dump the query result.

```php
$db->query('SELECT * FROM table')->dump();
```

### Var dump and die

`dumpd()`

Var dump the query result and die.

```php
$db->query('SELECT * FROM table')->dumpd();
```

### Print result

`printr()`

Prints the query result in a nice looking way.

```php
$db->query('SELECT * FROM table')->printr();
```

### Print result and die

`printrd()`

Prints the query result in a nice looking way and die.

```php
$db->query('SELECT * FROM table')->printrd();
```

## General methods

### Get Pdo

`getPdo()`

Returns the PDO object.

```php
$db->getPdo();
```

### Get last id

`getLastId()`

Returns the last inserted ID in the database.

```php
$db->getLastId();
```

### Get last statement

`getLastStmt()`

Returns the last PDO statement executed.

```php
$db->getLastStmt();
```

### Get last query

`getLastSql()`

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

`tableExists(string $table)`

Returns true if the specified table exists, false otherwise.

```php
$db->tableExists('users');
```

### Column exists

`columnExists(string $table, string $column)`

Returns true if the specified table and column exists, false otherwise.

```php
$db->columnExists('users', 'user_id');
```

The first parameter is the table where the column is, the second is the column name.

### Get schema

`getSchema([string $table])`

Returns the database schema

```php
$db->getSchema();
```

Example result:
```php
Array
(
    //Table category
    [category] => Array
        (
            [0] => Array
                (
                    [Field] => category_id
                    [Type] => int(11)
                    [Null] => NO
                    [Key] => PRI
                    [Default] =>
                    [Extra] => auto_increment
                )

            [1] => Array
                (
                    [Field] => name
                    [Type] => varchar(155)
                    [Null] => NO
                    [Key] =>
                    [Default] =>
                    [Extra] =>
                )
        )

    //Table portfolio
    [portfolio] => Array
        (
            [0] => Array
                (
                    [Field] => portfolio_id
                    [Type] => int(11)
                    [Null] => NO
                    [Key] => PRI
                    [Default] =>
                    [Extra] => auto_increment
                )

            [1] => Array
                (
                    [Field] => title
                    [Type] => varchar(150)
                    [Null] => NO
                    [Key] =>
                    [Default] =>
                    [Extra] =>
                )

            [2] => Array
                (
                    [Field] => category_id
                    [Type] => int(11)
                    [Null] => NO
                    [Key] => MUL
                    [Default] =>
                    [Extra] =>
                )
        )
)
```
You can pass a table name to get only the schema of that table

```php
$db->getSchema('portfolio');
```

## Fast methods

The DB class has some fast methods you can use.

### Insert

`insert(string $table, array $data)`

Inserts the given data into the specified table.

The first parameter must be the table name where the data will be inserted, the second parameter must be an associative array with data.

Take in mind that the array keys will be directly mapped to the column names.

```php
$db->insert('product', [
    'name'     => 'phone',
    'model'    => 'PHN001',
    'quantity' => 5
]);
```

That will be the same as `INSERT INTO 'product' (name, model, quantity) VALUES ('phone', 'PHN001', '5')`.

### Select

`select(string $table[, string $conditions[, ...$args ]])`  

Runs a select query in the specified table. This method returns the result as an associative array, and accepts dot notation.

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

`count(string $table[, string $conditions[, ...$args ]])`

Returns the number of rows in the specified table, as an `int`.

```php
$db->count('users');
$db->count('users', 'id = ?', 1);
```

Equivalent to:

`SELECT COUNT(*) * FROM users`  
`SELECT COUNT(*) * FROM users WHERE id = 1`.

### Delete

`delete(string $table[, string $conditions[, ...$args ]])`  

Deletes the rows in the specified table.
This method returns `true` in case of success, `false` otherwise.

```php
$db->delete('users');
$db->delete('users', 'id = ?', 1);
```

Equivalent to:

`DELETE * FROM users`  
`DELETE * FROM users WHERE id = 1`.
