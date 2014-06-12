Mongo
=====

Minimal MongoDb for Laravel apps.

This package provides simple to use MongoDB structure and authentication, to get you started with MongoDB in Laravel in less than 10 seconds.

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

### Authentication

This package comes bundled with the authentication driver for MongoDB. To set it up, do these steps.

1. Change the driver in `app/config/auth.php` to 'mongo';

```php
'driver' => 'mongo'
```

2. Next, add these two properties (probably next to 'table' property) in the same `app/config/auth.php` file.

```php
/*
|--------------------------------------------------------------------------
| Authentication Collection
|--------------------------------------------------------------------------
|
| When using the "Mongo" authentication driver, we need to know which
| collection should be used to retrieve your users. We have chosen a basic
| default value but you may easily change it to any table you like.
|
*/

'collection' => 'users',

/*
|--------------------------------------------------------------------------
| Hashed or Encrypted Password
|--------------------------------------------------------------------------
|
| In some cases, you may need to store passwords encrypted as opposed to
| hashed generally. For instance, API secret keys for your users can be stored
| encrypted and not hashed, as they are to be decrypted and shown to
| users in their accounts (and storing them in plain text is a very bad idea).
| By default, we assume that the password will be hashed. If it is encrypted,
| set the following option to true.
|
*/

'encrypted_password' => false,
```

That's it! Now, you can authenticate your users using MongoDB. No other change is required in your whole code - it just works!

***

We are not sure, if we will extend the functionalities of this package. We wanted something to get started quickly. If you want something more extensive, Jens has a great package for you: [https://github.com/jenssegers/Laravel-MongoDB](https://github.com/jenssegers/Laravel-MongoDB).