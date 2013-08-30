# Basic NCIP php library and Laravel package

[![Build Status](https://travis-ci.org/danmichaelo/ncip.png?branch=master)](https://travis-ci.org/danmichaelo/ncip)
[![Coverage Status](https://coveralls.io/repos/danmichaelo/ncip/badge.png?branch=master)](https://coveralls.io/r/danmichaelo/ncip?branch=master)

Currently implementing only a small subset of the NCIP services.

## Installation:

Add the package to the `require` attribute of your `composer.json` file.

```json
{
    "require": {
        "danmichaelo/ncip": "dev-master"
    },
}
``` 

and do `composer update`.

### Additional steps to install as a Laravel 4 package

1. Add `'Danmichaelo\NcipServiceProvider',` to the list of service providers in `app/config/app.php`
2. Add `'NcipClient' => 'Danmichaelo\Ncip\NcipClient',` to the list of class aliases in `app/config/app.php`
3. In console run `php artisan config:publish danmichaelo/ncip` to create the config file `app/config/packages/danmichael/ncip/config.php`

## Standalone use without Laravel:

	require_once('vendor/autoload.php');
	use Danmichaelo\Ncip\NcipClient;

	$options = array('url' => 'http://...', 'agency_id' => '...');
	$ncip = new NcipClient($options);


## Example:

	$ncip = new NcipClient();
	$response = $ncip->lookupUser($user_id);
	if ($response['exists']) {
		echo 'Hello ' . $response['firstname'] . ' ' . $response['lastname'];
	else:
		echo 'User not found';
