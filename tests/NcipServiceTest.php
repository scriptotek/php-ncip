<?php namespace Danmichaelo\Ncip;

class NcipServiceTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		$this->service = new NcipService;
	}

	public function testParseXml() {

		$xml = $this->service->parseXml('<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
			   <ns1:LookupUserResponse>
			   </ns1:LookupUserResponse>
			</ns1:NCIPMessage>');

		$this->assertInstanceOf('Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement', $xml);

		$msg = $xml->first('/ns1:NCIPMessage');
		$this->assertEquals(1, $msg->count());
	}

	/**
	 * @expectedException Danmichaelo\Ncip\InvalidNcipResponseException
	 */
	public function testInvalidXml() {
		$this->service->parseXml('
			<ns1:NCIPMess
		');
	}

}
