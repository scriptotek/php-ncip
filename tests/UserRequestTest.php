<?php namespace Danmichaelo\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;


class UserRequestTest extends \PHPUnit_Framework_TestCase {

	public function testXml() {
		$userId = 'xx00000001';
		$req = new UserRequest($userId);
		$xml = $req->xml();

		$this->assertSelectCount('LookupUser', 1, $xml);
		$this->assertContains('<ns1:UserIdentifierValue>' . $userId . '</ns1:UserIdentifierValue>', $xml);
	}

}
