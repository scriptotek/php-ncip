<?php namespace Danmichaelo\Ncip;

use Mockery as m;

class NcipConnectorTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp() {
		$this->url = 'http://nowhere.null';
		$this->user_agent = 'abc';
		$this->conn = new NcipConnector($this->url, $this->user_agent);
	}

	public function testConstructor() {
		$this->assertEquals($this->url, $this->conn->url);
		$this->assertEquals($this->user_agent, $this->conn->user_agent);
	}

	// TODO: testPost requires mocking curl...

}
