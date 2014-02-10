<?php namespace Danmichaelo\Ncip;

use Mockery as m;

class NcipServerTest extends \PHPUnit_Framework_TestCase {

	protected $agencyId;
	protected $server;

	protected function setUp() {
		$this->agencyId = 'x';
		$this->server = new NcipServer($this->agencyId);
	}

	public function testLookupUser() {
		$userId = 'ex0000001';
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:LookupUser>
					<ns1:UserId>
						<ns1:UserIdentifierValue>' . $userId . '</ns1:UserIdentifierValue>
					</ns1:UserId>
					<ns1:LoanedItemsDesired/>
					<ns1:RequestedItemsDesired/>
				</ns1:LookupUser>
			</ns1:NCIPMessage>');

		$this->assertInstanceOf('Danmichaelo\Ncip\UserRequest', $request);
		$this->assertEquals($userId, $request->userId);
	}

	public function testRenewItem() {
		$userId = 'ex0000001';
		$itemId = 'doc0000001';
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:RenewItem>
					<ns1:AuthenticationInput>
						<ns1:AuthenticationInputData>' . $userId . '</ns1:AuthenticationInputData>
						<ns1:AuthenticationDataFormatType>text</ns1:AuthenticationDataFormatType>
						<ns1:AuthenticationInputType>User Id</ns1:AuthenticationInputType>
					</ns1:AuthenticationInput>
					<ns1:ItemId>
					   <ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:RenewItem>
			</ns1:NCIPMessage>');

		$this->assertInstanceOf('Danmichaelo\Ncip\RenewRequest', $request);
		$this->assertEquals($userId, $request->userId);
		$this->assertEquals($itemId, $request->itemId);
	}

	public function testCheckOutItem() {
		$agencyId = 'q';
		$userId = 'ex0000001';
		$itemId = 'doc0000001';
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:CheckOutItem>
					<ns1:UserId>
						<ns1:UserIdentifierValue>' . $userId . '</ns1:UserIdentifierValue>
					</ns1:UserId>
					<ns1:ItemId>
					   <ns1:AgencyId>' . $agencyId . '</ns1:AgencyId>
					   <ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:CheckOutItem>
			</ns1:NCIPMessage>');

		$this->assertInstanceOf('Danmichaelo\Ncip\CheckOutRequest', $request);
		$this->assertEquals($agencyId, $request->agencyId);
		$this->assertEquals($userId, $request->userId);
		$this->assertEquals($itemId, $request->itemId);
	}

	public function testCheckInItem() {
		$agencyId = 'q';
		$itemId = 'doc0000001';
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:CheckInItem>
					<ns1:ItemId>
					   <ns1:AgencyId>' . $agencyId . '</ns1:AgencyId>
					   <ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:CheckInItem>
			</ns1:NCIPMessage>');

		$this->assertInstanceOf('Danmichaelo\Ncip\CheckInRequest', $request);
		$this->assertEquals($agencyId, $request->agencyId);
		$this->assertEquals($itemId, $request->itemId);
	}

}
