<?php namespace Scriptotek\Ncip;

class ResponseTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		date_default_timezone_set('UTC');
	}
	
	public function testParseDateTime() {
		$nr = new Response();
		$date1 = $nr->parseDateTime('2013-09-21T18:54:39.718+02:00');
		$date2 = new \DateTime('2013-09-21T18:54:39+02:00');
		$date3 = new \DateTime('2013-09-21T19:54:39+02:00');

		$this->assertInstanceOf('DateTime', $date1);
		$this->assertEquals($date1->getTimestamp(), $date2->getTimestamp());
		$this->assertNotEquals($date1->getTimestamp(), $date3->getTimestamp());
	}

}
