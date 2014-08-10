<?php namespace Scriptotek\Ncip\Test;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;
use Scriptotek\Ncip\CheckInRequest;


class CheckInRequestTest extends \PHPUnit_Framework_TestCase {

	public function testXml() {
		$agencyId = 'k';
		$itemId = 'exdoc10002';
		$req = new CheckInRequest($agencyId, $itemId);
		$xml = $req->xml();

		$this->assertTrue($req->is('CheckInItem'));
		$this->assertSelectCount('CheckInItem', 1, $xml);
		$this->assertContains('<ns1:AgencyId>' . $agencyId . '</ns1:AgencyId>', $xml);
		$this->assertContains('<ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>', $xml);
	}

}
