<?php namespace Danmichaelo\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;


class RenewRequestTest extends \PHPUnit_Framework_TestCase {

	public function testXml() {
		$userId = 'xx00000001';
		$itemId = 'exdoc10002';
		$req = new RenewRequest($userId, $itemId);
		$xml = $req->xml();

		$this->assertTrue($req->is('RenewItem'));
		$this->assertSelectCount('RenewItem', 1, $xml);
		$this->assertContains('<ns1:AuthenticationInputData>' . $userId . '</ns1:AuthenticationInputData>', $xml);
		$this->assertContains('<ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>', $xml);
	}

}
