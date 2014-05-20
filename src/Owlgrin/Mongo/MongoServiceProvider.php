<?php namespace Owlgrin\Mongo;

use Owlgrin\Mongo\Connection;
use Illuminate\Support\ServiceProvider;

class MongoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

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
