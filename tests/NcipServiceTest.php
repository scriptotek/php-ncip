<?php namespace Danmichaelo\Ncip;

class NcipServiceTest extends \PHPUnit_Framework_TestCase {

	protected $agencyId;

	protected function setUp() {
		$this->agencyId = 'x';
		$this->service = new NcipService($this->agencyId);
	}

	public function testParseXml() {

		$xml = $this->service->parseXml('<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
			   <ns1:LookupUserResponse>
			   </ns1:LookupUserResponse>
			</ns1:NCIPMessage>');

		$this->assertInstanceOf('Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement', $xml);

		$msg = $xml->first('/ns1:NCIPMessage');
		$this->assertEquals(1, $msg->count('ns1'));
	}

}
