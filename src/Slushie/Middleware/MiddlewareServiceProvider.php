<?php namespace Slushie\Middleware;

use Illuminate\Support\ServiceProvider;

class MiddlewareServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

  /**
   * @inheritdoc
   */
  public function boot() {
    parent::boot();

    $this->app['middleware']->register();
  }

  /**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
    $this->app->singleton('middleware', function($app) {
      return new Loader($app);
    });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('middleware');
	}

}