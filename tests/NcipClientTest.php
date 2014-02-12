<?php namespace Danmichaelo\Ncip;

use Mockery as m;

class NcipClientTest extends \PHPUnit_Framework_TestCase {

	protected $agencyId;
	protected $ncip;
	
	protected function setUp() {

	}

	public function setUpMock($xml_response)
	{
		$mock = m::mock('Danmichaelo\Ncip\NcipConnector');
		$mock->shouldReceive('post')
			->once()
			->andReturn($xml_response);
		$this->agencyId = 'x';
		$this->ncip = new NcipClient($mock);
	}

	public function testLookupUser() {

		$this->setupMock('
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
			   <ns1:LookupUserResponse>
			      <ns1:UserId>
			         <ns1:AgencyId>x</ns1:AgencyId>
			         <ns1:UserIdentifierValue>abc01010101</ns1:UserIdentifierValue>
			      </ns1:UserId>
			      <ns1:LoanedItem>
			         <ns1:ItemId>
			            <ns1:ItemIdentifierValue>xxxxxxxx</ns1:ItemIdentifierValue>
			         </ns1:ItemId>
			         <ns1:ReminderLevel>1</ns1:ReminderLevel>
			         <ns1:DateDue>2013-09-16T20:29:39.606+02:00</ns1:DateDue>
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
			               <ns1:ElectronicAddressData>d.duck@andeby.no</ns1:ElectronicAddressData>
			            </ns1:ElectronicAddress>
			         </ns1:UserAddressInformation>
			         <ns1:UserAddressInformation>
			            <ns1:UserAddressRoleType>sms</ns1:UserAddressRoleType>
			            <ns1:ElectronicAddress>
			               <ns1:ElectronicAddressType>sms</ns1:ElectronicAddressType>
			               <ns1:ElectronicAddressData>10000000</ns1:ElectronicAddressData>
			            </ns1:ElectronicAddress>
			         </ns1:UserAddressInformation>
			         <ns1:UserAddressInformation>
			            <ns1:UserAddressRoleType>Permanent</ns1:UserAddressRoleType>
			            <ns1:PhysicalAddress>
			               <ns1:UnstructuredAddress>
			                  <ns1:UnstructuredAddressType>Newline-Delimited Text</ns1:UnstructuredAddressType>
			                  <ns1:UnstructuredAddressData>Apalveien 13
			0200 Andeby</ns1:UnstructuredAddressData>
			               </ns1:UnstructuredAddress>
			               <ns1:PhysicalAddressType>Postal Address</ns1:PhysicalAddressType>
			            </ns1:PhysicalAddress>
			         </ns1:UserAddressInformation>
			         <ns1:UserLanguage>eng</ns1:UserLanguage>
			      </ns1:UserOptionalFields>
			   </ns1:LookupUserResponse>
			</ns1:NCIPMessage>
		');

		$response = $this->ncip->lookupUser('test123456');

		$this->assertInstanceOf('Danmichaelo\Ncip\UserResponse', $response);
		$this->assertEquals('Donald', $response->firstName);

	}


	/**
 	 * @expectedException Danmichaelo\Ncip\InvalidNcipResponseException
 	 */
	public function testInvalidResponse() {
		$this->setupMock('
			<ns1:NCIPMess
		');

		$response = $this->ncip->lookupUser('test123456');
	}

}
