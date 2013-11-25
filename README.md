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

If you use the package *with* Laravel, options are pulled from `app/config/packages/danmichael/ncip/config.php` and the `NcipConnector` is injected into the `NcipClient` automatically.
Otherwise, you have to do this manually, as shown below:

```php
require_once('vendor/autoload.php');
use Danmichaelo\Ncip\NcipConnector,
    Danmichaelo\Ncip\NcipClient;

$conn = new NcipConnector(array(
	'url' => 'http://eksempel.com/NCIPResponder',
	'user_agent' => 'My NCIP client/0.1'
));
$client = new NcipClient($conn, array(
	'agency_id' => 'a'
));
```

## Example:

```php
$client = new NcipClient();
$response = $client->lookupUser($user_id);
if ($response['exists']) {
	echo 'Hello ' . $response->firstName . ' ' . $response->lastName;
else:
	echo 'User not found';
```

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/danmichaelo/ncip/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

