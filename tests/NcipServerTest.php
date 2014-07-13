<?php namespace Scriptotek\Ncip;

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

		$this->assertInstanceOf('Scriptotek\Ncip\UserRequest', $request);
		$this->assertTrue($request->is('LookupUser'));
		$this->assertEquals($userId, $request->userId);
	}

	public function testLookupItem() {
		$itemId = 'doc1234567';
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:LookupItem>
					<ns1:ItemId>
					   <ns1:ItemIdentifierType>Accession Number</ns1:ItemIdentifierType>
					   <ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:LookupItem>
			</ns1:NCIPMessage>');

		$this->assertInstanceOf('Scriptotek\Ncip\ItemRequest', $request);
		$this->assertTrue($request->is('LookupItem'));
		$this->assertEquals($itemId, $request->itemId);
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

		$this->assertInstanceOf('Scriptotek\Ncip\RenewRequest', $request);
		$this->assertTrue($request->is('RenewItem'));
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

		$this->assertInstanceOf('Scriptotek\Ncip\CheckOutRequest', $request);
		$this->assertTrue($request->is('CheckOutItem'));
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

		$this->assertInstanceOf('Scriptotek\Ncip\CheckInRequest', $request);
		$this->assertTrue($request->is('CheckInItem'));
		$this->assertEquals($agencyId, $request->agencyId);
		$this->assertEquals($itemId, $request->itemId);
	}

	/**
     * @expectedException Scriptotek\Ncip\InvalidNcipRequestException
     */
	public function testInvalidRequestName() {
		$itemId = 'doc0000001';
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:DestroyItem>
					<ns1:ItemId>
					   <ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:DestroyItem>
			</ns1:NCIPMessage>');
	}

	/**
     * @expectedException Scriptotek\Ncip\InvalidNcipRequestException
     */
	public function testInvalidMessageContainer() {
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<html></html>
		');
	}

	/**
     * @expectedException Scriptotek\Ncip\InvalidNcipRequestException
     */
	public function testNoRequest() {
		$request = $this->server->parseRequest('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
			</ns1:NCIPMessage>
		');
	}

}
