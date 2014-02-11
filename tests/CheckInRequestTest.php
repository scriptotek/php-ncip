<?php namespace Danmichaelo\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;


class CheckInRequestTest extends \PHPUnit_Framework_TestCase {

	public function testXml() {
		$agencyId = 'k';
		$itemId = 'exdoc10002';
		$req = new CheckInRequest($agencyId, $itemId);
		$xml = $req->xml();

		$this->assertSelectCount('CheckInItem', 1, $xml);
		$this->assertContains('<ns1:AgencyId>' . $agencyId . '</ns1:AgencyId>', $xml);
		$this->assertContains('<ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>', $xml);
	}

}
