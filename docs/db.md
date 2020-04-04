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
    'dbms'        => 'mysql',
    'server'      => 'localhost',
    'db'          => 'wolff',
    'db_username' => 'root',
    'db_password' => '12345',
];

$db = new Wolff\Core\DB($credentials);
```

Both examples are right.

## Running queries

`query(string $sql[, $args])`

The `query` method returns a `Wolff\Core\Query` object.

```php
$db->query('SELECT * FROM table');
```

You can prepare a query passing an array or single variable as the second parameter.

```php
$db->query('SELECT * FROM users WHERE id = ?', $id);
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

`dumpd()`

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

Returns true if the specified column exists, false otherwise.

```php
$db->columnExists('users', 'user_id');
```

The first parameter is the table where the column is, the second is the column name.

### Get schema

`getSchema([string $table])`

Returns the database schema

```php
DB::getSchema();
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
    'model'    => 'PHN001'
    'quantity' => 5
]);
```

That will be the same as `INSERT INTO 'product' (name, model, quantity) VALUES ('phone', 'PHN001', '5')`.

### All

The `DB` class has the following methods for running queries.

`selectAll(string $table[, string $conditions[, $args ]])`  
`countAll(string $table[, string $conditions[, $args ]])`  
`deleteAll(string $table[, string $conditions[, $args ]])`  


_Warning: The conditions parameter must NOT come from external/user input since it's NOT escaped._

```php
$db->selectAll('users');
$db->selectAll('users', 'id = ?', [ 1 ]);
```

Equivalent to:

`SELECT * FROM users`  
`SELECT * FROM users WHERE id = 1`.

```php
$db->countAll('users');
$db->countAll('users', 'id = ?', [ 1 ]);
```

Equivalent to:

`SELECT COUNT(*) * FROM users`  
`SELECT COUNT(*) * FROM users WHERE id = 1`.

```php
$db->deleteAll('users');
$db->deleteAll('users', 'id = ?', [ 1 ]);
```

Equivalent to:

`DELETE * FROM users`  
`DELETE * FROM users WHERE id = 1`.
