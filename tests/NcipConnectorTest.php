<?php namespace Scriptotek\Ncip\Test;

use Mockery as m;
use Scriptotek\Ncip\NcipConnector;


class NcipConnectorTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp() {
		$this->url = 'http://nowhere.null';
		$this->user_agent = 'abc';
		$this->agency_id = 'a';
		$this->conn = new NcipConnector($this->url, $this->user_agent, $this->agency_id);
	}

	public function testConstructor() {
		$this->assertEquals($this->url, $this->conn->url);
		$this->assertEquals($this->user_agent, $this->conn->user_agent);
		$this->assertEquals($this->agency_id, $this->conn->agency_id);
	}

	// TODO: testPost requires mocking curl...

}
