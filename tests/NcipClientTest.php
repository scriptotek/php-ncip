<?php namespace Scriptotek\Ncip\Test;

use Mockery as m;
use Carbon\Carbon;
use Scriptotek\Ncip\NcipClient;

class NcipClientTest extends TestCase {

	protected $agencyId;
	protected $ncip;
	
	protected function setUp() {
		$this->conn = m::mock('Scriptotek\Ncip\NcipConnector');
		$this->ncip = new NcipClient($this->conn);
	}

	public function testLookupUser() {

		$this->conn->shouldReceive('post')
			->once()
			->andReturn($this->dummyUserResponse(array(
				'userId' => 'abc01010101',
				'firstName' => 'Donald',
			))->xml());

		$response = $this->ncip->lookupUser('test123456');

		$this->assertInstanceOf('Scriptotek\Ncip\UserResponse', $response);
		$this->assertEquals('Donald', $response->firstName);
	}

	/**
 	 * @expectedException Scriptotek\Ncip\InvalidNcipResponseException
 	 */
	public function testInvalidResponse() {
		$this->conn->shouldReceive('post')
			->once()
			->andReturn('<ns1:NCIPMess');

		$response = $this->ncip->lookupUser('test123456');
	}

	public function testEvents()
	{
		$u = 'Mr. Nelson';
		$i = 'Unit testing decoded';
		$this->conn->shouldReceive('post')
			->once()
			->andReturn($this->dummyCheckoutResponse(array(
				'userId' => $u,
				'itemId' => $i,
			  ))->xml());

		$listenerCalled = 0;
		$receivedUserId = '';
		$receivedItemId = '';
		$this->ncip->on('request.checkout', function($userId, $itemId) use (&$listenerCalled, &$receivedUserId, &$receivedItemId) {
			$listenerCalled++;
			$receivedUserId = $userId;
			$receivedItemId = $itemId;
		});

		$this->assertEquals(0, $listenerCalled);
		$this->ncip->checkOutItem($u, $i);
        $this->assertEquals(1, $listenerCalled);
        $this->assertEquals($u, $receivedUserId);
        $this->assertEquals($i, $receivedItemId);
	}

}
