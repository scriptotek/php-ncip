<?php namespace Danmichaelo\Ncip;

use Illuminate\Support\ServiceProvider;

class NcipServiceProvider extends ServiceProvider {

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
		$this->app['ncip'] = $this->app->share(function($app)
		{
			$conn = new NcipConnector($app['config']['ncip.url'], $app['config']['ncip.user_agent']);
			return new NcipClient($conn, $app['config']['ncip.agency_id']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('ncip');
	}

}
