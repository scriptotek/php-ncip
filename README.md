# Basic NCIP php library and Laravel package

[![Build Status](http://img.shields.io/travis/scriptotek/php-ncip.svg?style=flat)](https://travis-ci.org/scriptotek/php-ncip)
[![Coverage Status](http://img.shields.io/coveralls/scriptotek/php-ncip.svg?style=flat)](https://coveralls.io/r/scriptotek/php-ncip?branch=master)
[![Latest Stable Version](http://img.shields.io/packagist/v/danmichaelo/ncip.svg?style=flat)](https://packagist.org/packages/danmichaelo/ncip)
[![Total Downloads](http://img.shields.io/packagist/dt/danmichaelo/ncip.svg?style=flat)](https://packagist.org/packages/danmichaelo/ncip)
[![License](http://img.shields.io/packagist/l/danmichaelo/ncip.svg?style=flat)](https://packagist.org/packages/danmichaelo/ncip)


**php-ncip** is a php package for parsing and formatting NCIP request and response messages. Development has been guided by a desire for a simple API rather than a complete one. Only a small subset of the NCIP specification is currently covered, but suggestions for additions are welcome.

## Installation:

### Composer

Add the package to the `require` list of your `composer.json` file.

```json
{
    "require": {
        "danmichaelo/ncip": "dev-master"
    },
}
``` 

and run `composer install` to get the latest version of the package.

### Additional steps for Laravel 4

The package comes with a Laravel 4 service provider that you can install if you like. It comes with a config file, so you can set settings there instead of passing them to the constructor.

To register the service provider, add `'Scriptotek\NcipServiceProvider',` to the list of `providers` in `app/config/app.php`. Then run `php artisan config:publish danmichaelo/ncip` in your terminal to create the config file `app/config/packages/danmichael/ncip/config.php`.

## Usage:

### Construction

To construct a client, you need to specify the url to the NCIP srvice, a freely choosen user agent string for your client, and the agency id. The agency id identifies your institution, as specified in the NCIP protocol.

```php
require_once('vendor/autoload.php');
use Scriptotek\Ncip\NcipConnector,
    Scriptotek\Ncip\NcipClient;

$ncipUrl = 'http://eksempel.com/NCIPResponder';
$userAgent = 'My NCIP client/0.1';
$agencyId = 'a';

$conn = new NcipConnector($ncipUrl, $userAgent, $agencyId);
$client = new NcipClient($conn);
```

To construct a server:

```php
require_once('vendor/autoload.php');
use Scriptotek\Ncip\NcipServer;

$server = new NcipServer;
```

If you have registered the Laravel 4 service provider, the classes can be constructed through the applications container instead:

```php
$client = App::make('ncip.client');
$server = App::make('ncip.server');

// Or if you have access to an instance of the application.
$client = $app['ncip.client'];
$server = $app['ncip.server'];
```

The settings are now pulled from `app/config/packages/danmichael/ncip/config.php` instead, and the `NcipConnector` is injected into the `NcipClient` automatically.

### Client example:

```php
$user = $client->lookupUser('abc123');
if ($user->exists) {
	echo 'Hello ' . $user->firstName . ' ' . $user->lastName;
} else {
	echo 'User not found';
}
```

### Server example:

```php
$postData = file_get_contents('php://input');
$request = $server->parseRequest($postData);

if ($request->is('LookupUser')) {

	$response = new UserResponse;
	$response->userId = $request->userId;
	$response->firstName = 'Meriadoc';
	$response->lastName = 'Brandybuck';
	echo $response->xml();

}
```

### Events:

The client emits events on each request to the server. This can be useful to implement logging.
The events are `request.item`, `request.user`, `request.checkout`, `request.checkin` and `request.renew`.

```php
$client->on('request.checkout', function($userId, $itemId) {
	$log->addInfo('Requested checkout of item "' . $itemId . '" for user "' . $userId . '"');
}
```
