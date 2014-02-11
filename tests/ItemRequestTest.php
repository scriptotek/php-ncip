<?php namespace Danmichaelo\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;


class ItemRequestTest extends \PHPUnit_Framework_TestCase {

	public function testXml() {
		$itemId = 'doc123456789';
		$req = new ItemRequest($itemId);
		$xml = $req->xml();

		$this->assertSelectCount('LookupItem', 1, $xml);
		$this->assertContains('<ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>', $xml);
	}

}
