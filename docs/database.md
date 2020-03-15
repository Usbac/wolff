You can run queries using the DB connection. First you need to `use Core\DB` in your class, then you can use the following methods.

The `run` method returns a Query object.

```php
DB::run('SELECT * FROM table');
```

You can prepare a query passing an array or single variable as the second parameter.

```php
DB::run('SELECT * FROM users WHERE id = ?', $id); 
```

### Is enabled

You can check whetever or not the database system is enabled with the `isEnabled` method.

```php
DB::isEnabled();
```

That returns true if the database system is enabled, false otherwise.

## Query methods

### Get

Get the query result as an associative array with the `get` method.

```php
DB::run('SELECT * FROM table')->get();
```

### To Json

Get the result as a JSON instead of an array.

```php
DB::run('SELECT * FROM table')->toJson(); 
```

### Limit

Get the query result as an associative array sliced.

```php
DB::run('SELECT * FROM table')->limit(0, 5);
//Only the 5 first rows will be returned
```

### First

Get the first element of the query result with the `first` method.

```php
DB::run('SELECT * FROM table')->first();
```

You can pass a column name as parameter to the `first` method.

That will return only the specified column value of the first element.

```php
DB::run('SELECT * FROM table')->first('column');
```

### Count

Count the query rows.

```php
DB::run('SELECT * FROM table')->count();
```

### Pick

Get the query result only with the specified columns.

```php
DB::run('SELECT * FROM users')->pick('name');
```

That would return something like this:
```
Array
(
    [0] => Margaret Brown
    [1] => Thomas Andrews
    [2] => Bruce Ismay
)
```

```php
DB::run('SELECT * FROM users')->pick('name', 'age');
```

That would return something like this:
```
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

Var dump the query result.

```php
DB::run('SELECT * FROM table')->dump(); 
```

### Var dump and die

Var dump the query result and die.

```php
DB::run('SELECT * FROM table')->dumpd(); 
```

### Print result

Print the query result in a nice looking way.

```php
DB::run('SELECT * FROM table')->printr(); 
```

### Print result and die

Print the query result in a nice looking way and die.

```php
DB::run('SELECT * FROM table')->printrd(); 
```

## General methods

### Get Pdo

Returns the PDO object.

```php
DB::getPdo();
```

### Get last id

Returns the last inserted ID in the database.

```php
DB::getLastId();
```

### Get last statement

Returns the last PDO statement executed.

```php
DB::getLastStmt();
```

### Get last query

Returns the last query executed.

```php
DB::getLastSql();
```

And you can get its arguments with `getLastArgs`.

```php
DB::getLastArgs();
```

Finally you can re run the last query:

```php
DB::runLastSql();
```

### Table exists

Returns true if the specified table exists, false otherwise.

```php
DB::tableExists('users');
```

### Column exists

Returns true if the specified column exists, false otherwise.

```php
DB::columnExists('users', 'user_id');
```

The first parameter is the table where the column is, the second is the column name.

### Get schema

Returns the complete database schema 

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
                    [Field] => description
                    [Type] => varchar(255)
                    [Null] => NO
                    [Key] => 
                    [Default] => 
                    [Extra] => 
                )

            [3] => Array
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

### Get table schema

Returns the schema of the specified table.

```php
DB::getTableSchema('user');
```

## Fast methods

The DB class has some fast methods you can use

### Insert

Will insert the given data into the specified table.

The first parameter must be the table name where the data will be inserted, the second parameter must be an associative array with data.

Take in mind that the array keys will be directly mapped to the column names.

```php
DB::insert('product', [
        'name'     => 'phone',
        'model'    => 'PHN001'
        'quantity' => 5
]);
```

That will be the same as `INSERT INTO 'product' (name, model, quantity) VALUES ('phone', 'PHN001', '5')`.

### Select all

Will return the result of a `SELECT * FROM` query.
```php
DB::selectAll('users'); 
```
Equivalent to: `SELECT * FROM users`.

You can do the same with conditions:

```php
DB::selectAll('users', 'id = ?', [1]); 
```
Equivalent to: `SELECT * FROM users WHERE id = 1`.

The first parameter is the table name, the second is the where condition, the third is the argument array.

### Count all

Will return the result of a `SELECT COUNT(*) FROM` query.

```php
DB::countAll('users'); 
```

Equivalent to: `SELECT COUNT(*) FROM users`.

You can do the same with conditions:

```php
DB::countAll('users', 'id = ?', [1]); 
```

Equivalent to: `SELECT COUNT(*) FROM users WHERE id = 1`.

The first parameter is the table name, the second is the where condition, the third is the argument array.


### Delete all

Will return the result of a `DELETE FROM` query.
```php
DB::deleteAll('users'); 
```
Equivalent to: `DELETE FROM users`.

You can do the same with conditions:

```php
DB::deleteAll('users', 'id = ?', [1]); 
```

Equivalent to: `DELETE FROM users WHERE id = 1`.

The first parameter is the table name, the second is the where condition, the third is the argument array.
