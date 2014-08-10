<?php namespace Scriptotek\Ncip\Test;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;
use Scriptotek\Ncip\ItemRequest;


class ItemRequestTest extends \PHPUnit_Framework_TestCase {

	public function testXml() {
		$itemId = 'doc123456789';
		$req = new ItemRequest($itemId);
		$xml = $req->xml();

		$this->assertTrue($req->is('LookupItem'));
		$this->assertSelectCount('LookupItem', 1, $xml);
		$this->assertContains('<ns1:ItemIdentifierValue>' . $itemId . '</ns1:ItemIdentifierValue>', $xml);
	}

}
