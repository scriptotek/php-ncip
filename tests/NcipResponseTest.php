<?php

require 'vendor/autoload.php';

use Danmichaelo\Ncip\NcipResponse;

class NcipResponseTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		date_default_timezone_set('Europe/Oslo');
	}
	
	public function testParseDateTime() {
		$nr = new NcipResponse();
		$date1 = $nr->parseDateTime('2013-09-21T18:54:39.718+02:00');
		$date2 = new DateTime('2013-09-21 18:54:39');
		$date3 = new DateTime('2013-09-21 19:54:39');

		$this->assertInstanceOf('DateTime', $date1);
		$this->assertEquals($date1->getTimestamp(), $date2->getTimestamp());
		$this->assertNotEquals($date1->getTimestamp(), $date3->getTimestamp());
	}

}
