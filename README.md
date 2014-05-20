Mongo
=====

Minimal MongoDb for Laravel apps.

This package simply wraps the initialization of the MongoClient, to get you started with MongoDB in Laravel in less than 10 seconds.

### Installation

You can install this package by requiring it in your composer.json.

```js
"owlgrin/mongo": "dev-master"
```

The add the following service provider in your `app.php` configuration file.

```php
'Owlgrin\Mongo\MongoServiceProvider'
```

Lastly, you will have to list out your MongoDb connection credentials in your `database.php` configuration file. Add the connections like so:

```php
...
'connections' => array(
	...
	'mongo' => array(
		'driver'   => 'mongo',
		'host'     => 'localhost',
		'port'     => 27017,
		'username' => 'username',
		'password' => 'password',
		'database' => 'dbname'
	)
)
```

To leverage the benefits of replica sets, you can describe the configuration like this:

```php
...
'connections' => array(
	...
	'mongo' => array(
		'driver'   => 'mongo',
		'host'     => array('server1', 'server2'),
		'port'     => 27017,
		'username' => 'username',
		'password' => 'password',
		'database' => 'experiment',
		'options'  => array('replicaSet' => 'someReplicaSet')
	)
)
```

### Usage

With this package, you can use MongoDb like this:

```php
$user = DB::connection('mongo')
	->users
	->find(array('is_active' => 1));
```

If you are using MongoDb exclusively, you can set the `default` property in `database.php` configuration file to `mongo`, to make the usage easier, like so:

```php
$users = DB::collection('users')->find(array('is_active' => 1));
```

***

We are not sure, if we will extend the functionalities of this package. We wanted something to get started quickly. If you want something more extensive, Jens has a great package for you: [https://github.com/jenssegers/Laravel-MongoDB](https://github.com/jenssegers/Laravel-MongoDB).