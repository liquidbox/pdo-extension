# PHP PDO Extension

This is a PHP PDO extension class with a basic set of insert, select, update and
delete (CRUD) query helpers. Aimed at being intuitive, robust and
multifunctional.

## Usage

Database connection remains the same.

### Connection

Create a common ODBC database connection.

```php
$db = new PDO('odbc:dbname=hollywood;host=127.0.0.1', 'root');
```

Create a secure MySQL database connection.

```php
$db = new PDO(
	'mysql:dbname=hollywood;host=127.0.0.1',
	$config['db.username'],
	$config['db.password'],
    [
        PDO::MYSQL_ATTR_SSL_KEY  => '/path/to/client-key.pem',
        PDO::MYSQL_ATTR_SSL_CERT => '/path/to/client-cert.pem',
        PDO::MYSQL_ATTR_SSL_CA   => '/path/to/ca-cert.pem'
    ]
);
```

### query() Method Override

This extension overrides the query method to use the power of printf formatting.
Use an array in place of a string for the first argument. The order of values
are the same as printf starting with the
[format](http://php.net/manual/en/function.sprintf.php)
followed by as many arguments as needed.

```php
$res = $db->query(['UPDATE actor SET oscars = oscars + 1 WHERE id = %d', 4600]);
```

Passing a string remains functional as before.

### Query Helpers

The query helpers are designed to be intuitive by mirroring their SQL
counterparts in syntax.

#### Creating Records

Create a single record by passing the table name and an associative array of the
column-value pair(s) to set.

```php
$db->insert(
    'actor',
    [
        'name' => "Anna Kendrick",
		'gender' => "female",
        'born' => (new DateTime("August 9, 1985"))->format('Y-m-d')
    ]
);
```

Create multiple records by passing an array of associative arrays.

```php

```

#### Reading Records

Note: Short-circuited the ORDER BY clause.

```php
if ($page > 1) {
	$offset = ($page - 1) * $limit
}

$res = $db->select(
	['name', 'picture'],
	'actor',
	'name LIKE "%' . $middleName . '%" AND gender = "male"',
	isset($offset) ? [$offset, $limit] : $limit
);

$actors = $res->fetchAll();
```

#### Updating Records

```php
$db->update(
	'actor',
	['oscars' => 'oscars + 1'],
	'name = "Michael Thomas Green"'
);
```

```php
$db->update(
	'actor',
	[
		['latest_role' => "Moe",   'name' => "Moses Harry Horwitz"],
		['latest_role' => "Larry", 'name' => "Louis Feinberg"],
		['latest_role' => "Curly", 'name' => "Jerome Lester Horwitz"]
	]
);
```

#### Deleting Records

```php
$db->delete('actor', 'name = "Joey Tribbiani"');
```

## License

See the [LICENSE](LICENSE.txt) file for license rights and limitations (MIT).
