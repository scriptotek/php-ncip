<?php namespace Danmichaelo\Ncip;

use Illuminate\Support\ServiceProvider;

class NcipServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->package('danmichaelo/ncip');
	}

}
