<?php namespace Owlgrin\Mongo;

use Owlgrin\Mongo\Connection;
use Owlgrin\Mongo\Auth;
use Illuminate\Support\ServiceProvider;

class MongoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Extending it in boot method because 'auth' component is deferred
		$this->app['auth']->extend('mongo', function($app)
		{
			return new Auth\MongoUserProvider($app['db']->connection('mongo'), $app['hash'], $app['encrypter'], $app['config']['auth.collection'], $app['config']['auth.encrypted_password']);
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['db']->extend('mongo', function($config, $name)
		{
			return new Connection($config);
		});
	}

}
