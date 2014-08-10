<?php namespace Scriptotek\Ncip\Test;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;
use Scriptotek\Ncip\UserRequest;


class UserRequestTest extends \PHPUnit_Framework_TestCase {

	public function testXml() {
		$userId = 'xx00000001';
		$req = new UserRequest($userId);
		$xml = $req->xml();

		$this->assertTrue($req->is('LookupUser'));
		$this->assertSelectCount('LookupUser', 1, $xml);
		$this->assertContains('<ns1:UserIdentifierValue>' . $userId . '</ns1:UserIdentifierValue>', $xml);
	}

}
