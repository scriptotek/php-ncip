<?php namespace Scriptotek\Ncip\Test;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;
use Scriptotek\Ncip\UserResponse;


class UserResponseTest extends \PHPUnit_Framework_TestCase {

	protected $dummy_response_success = "
		<ns1:NCIPMessage xmlns:ns1=\"http://www.niso.org/2008/ncip\">
		   <ns1:LookupUserResponse>
		      <ns1:UserId>
		         <ns1:AgencyId>x</ns1:AgencyId>
		         <ns1:UserIdentifierValue>abcd010101</ns1:UserIdentifierValue>
		      </ns1:UserId>
		      <ns1:LoanedItem>
		         <ns1:ItemId>
		            <ns1:ItemIdentifierValue>xxxxxxxxx</ns1:ItemIdentifierValue>
		         </ns1:ItemId>
		         <ns1:ReminderLevel>1</ns1:ReminderLevel>
		         <ns1:DateDue>2013-09-16T19:48:50.628+02:00</ns1:DateDue>
		         <ns1:Amount>
		            <ns1:CurrencyCode>NOK</ns1:CurrencyCode>
		            <ns1:MonetaryValue>0</ns1:MonetaryValue>
		         </ns1:Amount>
		         <ns1:Title>Quantum computing</ns1:Title>
		         <ns1:Ext>
		            <ns1:BibliographicDescription>
		               <ns1:Author>Hirvensalo, Mika</ns1:Author>
		               <ns1:BibliographicRecordId>
		                  <ns1:BibliographicRecordIdentifier>000719641</ns1:BibliographicRecordIdentifier>
		                  <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
		               </ns1:BibliographicRecordId>
		               <ns1:Edition/>
		               <ns1:Pagination>XI, 190 s.</ns1:Pagination>
		               <ns1:PublicationDate>2001</ns1:PublicationDate>
		               <ns1:Publisher>Springer</ns1:Publisher>
		               <ns1:Title>Quantum computing</ns1:Title>
		               <ns1:Language>eng</ns1:Language>
		               <ns1:MediumType>Book</ns1:MediumType>
		            </ns1:BibliographicDescription>
		         </ns1:Ext>
		      </ns1:LoanedItem>
		      <ns1:UserOptionalFields>
		         <ns1:NameInformation>
		            <ns1:PersonalNameInformation>
		               <ns1:StructuredPersonalUserName>
		                  <ns1:GivenName>Donald</ns1:GivenName>
		                  <ns1:Surname>Duck</ns1:Surname>
		               </ns1:StructuredPersonalUserName>
		            </ns1:PersonalNameInformation>
		         </ns1:NameInformation>
		         <ns1:UserAddressInformation>
		            <ns1:UserAddressRoleType>mailto</ns1:UserAddressRoleType>
		            <ns1:ElectronicAddress>
		               <ns1:ElectronicAddressType>mailto</ns1:ElectronicAddressType>
		               <ns1:ElectronicAddressData>d.duck@andenett.com</ns1:ElectronicAddressData>
		            </ns1:ElectronicAddress>
		         </ns1:UserAddressInformation>
		         <ns1:UserAddressInformation>
		            <ns1:UserAddressRoleType>sms</ns1:UserAddressRoleType>
		            <ns1:ElectronicAddress>
		               <ns1:ElectronicAddressType>sms</ns1:ElectronicAddressType>
		               <ns1:ElectronicAddressData>10101010</ns1:ElectronicAddressData>
		            </ns1:ElectronicAddress>
		         </ns1:UserAddressInformation>
		         <ns1:UserAddressInformation>
		            <ns1:UserAddressRoleType>Permanent</ns1:UserAddressRoleType>
		            <ns1:PhysicalAddress>
		               <ns1:UnstructuredAddress>
		                  <ns1:UnstructuredAddressType>Newline-Delimited Text</ns1:UnstructuredAddressType>
		                  <ns1:UnstructuredAddressData>Apalveien 13\n0123 Andeby</ns1:UnstructuredAddressData>
		               </ns1:UnstructuredAddress>
		               <ns1:PhysicalAddressType>Postal Address</ns1:PhysicalAddressType>
		            </ns1:PhysicalAddress>
		         </ns1:UserAddressInformation>
		         <ns1:UserLanguage>eng</ns1:UserLanguage>
		      </ns1:UserOptionalFields>
		   </ns1:LookupUserResponse>
		</ns1:NCIPMessage>
 	  ";

	protected $dummy_response_fail = '
		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
		   <ns1:LookupUserResponse>
		      <ns1:Problem>
		         <ns1:ProblemType>User Authentication Failed</ns1:ProblemType>
		      </ns1:Problem>
		   </ns1:LookupUserResponse>
		</ns1:NCIPMessage>';

	protected $dummy_response_errorneous = '
		<ns1:NCIPMessage ';

	public function testParseDummySuccessResponse() {
		$dummy_response = new QuiteSimpleXMLElement($this->dummy_response_success);
		$response = new UserResponse($dummy_response);

		$date1 = new \DateTime('2013-09-16T19:48:50+02:00');

		$this->assertInstanceOf('Scriptotek\Ncip\UserResponse', $response);
		$this->assertTrue($response->exists);
		$this->assertEquals('x', $response->agencyId);
		$this->assertEquals('abcd010101', $response->userId);
		$this->assertEquals('Donald', $response->firstName);
		$this->assertEquals('Duck', $response->lastName);
		$this->assertEquals('eng', $response->lang);
		$this->assertEquals('d.duck@andenett.com', $response->email);
		$this->assertEquals('10101010', $response->phone);
		$this->assertEquals("Apalveien 13\n0123 Andeby", $response->postalAddress);

		$this->assertCount(1, $response->loanedItems);
		$this->assertEquals($date1->getTimestamp(), $response->loanedItems[0]['dateDue']->getTimestamp());

	}

	public function testParseDummyFailResponse() {
		$dummy_response = new QuiteSimpleXMLElement($this->dummy_response_fail);
		$response = new UserResponse($dummy_response);

		$this->assertInstanceOf('Scriptotek\Ncip\UserResponse', $response);
		$this->assertFalse($response->exists);
	}

	public function testParseEmptyUserResponse() {
		$response = new UserResponse;

		$this->assertInstanceOf('Scriptotek\Ncip\UserResponse', $response);
		$this->assertFalse($response->exists);
	}

	public function testXml()
	{
		$response = new UserResponse;
		$response->firstName = 'Meriadoc';
		$response->lastName = 'Brandybuck';
		$response->email = 'meriadoc.brandybuck@hobb.it';
		$response->loanedItems = array(
			array(
				'id' => '13k115558', 
				'dateDue' => new \DateTime('2014-12-12T00:00:00+02:00'), 
				'reminderLevel' => 2, 
				'title' => 'Neutrino cosmology'
			)
		);
		$xml = $response->xml();

		$this->assertContains('<ns1:GivenName>Meriadoc</ns1:GivenName>', $xml);
		$this->assertContains('<ns1:Surname>Brandybuck</ns1:Surname>', $xml);
		$this->assertContains('<ns1:ElectronicAddressData>meriadoc.brandybuck@hobb.it</ns1:ElectronicAddressData>', $xml);

		$this->assertSelectCount('LoanedItem', 1, $xml);
		$this->assertContains('<ns1:ItemIdentifierValue>13k115558</ns1:ItemIdentifierValue>', $xml);
		$this->assertContains('<ns1:ReminderLevel>2</ns1:ReminderLevel>', $xml);
		$this->assertContains('<ns1:DateDue>2014-12-12T00:00:00+0200</ns1:DateDue>', $xml);
		$this->assertContains('<ns1:Title>Neutrino cosmology</ns1:Title>', $xml);
	}

}
