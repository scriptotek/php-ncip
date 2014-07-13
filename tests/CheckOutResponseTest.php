<?php namespace Scriptotek\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class CheckOutResponseTest extends \PHPUnit_Framework_TestCase {

	protected $dummy_response_success = '
 	  <ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 	     <ns1:CheckOutItemResponse>
 	        <ns1:ItemId>
 	           <ns1:AgencyId>k</ns1:AgencyId>
 	           <ns1:ItemIdentifierValue>13k040189</ns1:ItemIdentifierValue>
 	        </ns1:ItemId>
 	        <ns1:UserId>
 	           <ns1:AgencyId>k</ns1:AgencyId>
 	           <ns1:UserIdentifierValue>xxxxxxxxxx</ns1:UserIdentifierValue>
 	        </ns1:UserId>
 	        <ns1:DateDue>2013-09-21T18:54:39.718+02:00</ns1:DateDue>
 	        <ns1:ItemOptionalFields>
 	           <ns1:BibliographicDescription>
 	              <ns1:Author>DuCharme, Bob</ns1:Author>
 	              <ns1:BibliographicRecordId>
 	                 <ns1:BibliographicRecordIdentifier>11447981x</ns1:BibliographicRecordIdentifier>
 	                 <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
 	              </ns1:BibliographicRecordId>
 	              <ns1:Edition/>
 	              <ns1:Pagination>XIII, 235 s., ill.</ns1:Pagination>
 	              <ns1:PublicationDate>2011</ns1:PublicationDate>
 	              <ns1:Publisher>O\'Reilly</ns1:Publisher>
 	              <ns1:Title>Learning SPARQL : querying and updating with SPARQL 1.1</ns1:Title>
 	              <ns1:Language>eng</ns1:Language>
 	              <ns1:MediumType>Book</ns1:MediumType>
 	           </ns1:BibliographicDescription>
 	        </ns1:ItemOptionalFields>
 	        <ns1:Ext>
 	           <ns1:UserOptionalFields>
 	              <ns1:UserLanguage>eng</ns1:UserLanguage>
 	           </ns1:UserOptionalFields>
 	        </ns1:Ext>
 	     </ns1:CheckOutItemResponse>
 	  </ns1:NCIPMessage>';

	protected $dummy_response_fail = '
		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
		   <ns1:CheckOutItemResponse>
		      <ns1:Problem>
		         <ns1:ProblemType>Item does not circulate</ns1:ProblemType>
		         <ns1:ProblemDetail>LTID:Finnes ikke                                                                </ns1:ProblemDetail>
		      </ns1:Problem>
		   </ns1:CheckOutItemResponse>
		</ns1:NCIPMessage>';

	protected $dummy_response_errorneous = '
		<ns1:NCIPMessage ';

	public function testParseDummySuccessResponse() {
		$dummy_response = new QuiteSimpleXMLElement($this->dummy_response_success);
		$response = new CheckOutResponse($dummy_response);
		$date1 = new \DateTime('2013-09-21T18:54:39+02:00');

		$this->assertInstanceOf('Scriptotek\Ncip\CheckOutResponse', $response);
		$this->assertTrue($response->success);
		$this->assertEquals($date1->getTimestamp(), $response->dateDue->getTimestamp());
		$this->assertEquals('Learning SPARQL : querying and updating with SPARQL 1.1', $response->bibliographic['title']);
		$this->assertEquals('13k040189', $response->itemId);

	}

	public function testParseDummyFailResponse() {
		$dummy_response = new QuiteSimpleXMLElement($this->dummy_response_fail);
		$response = new CheckOutResponse($dummy_response);

		$this->assertInstanceOf('Scriptotek\Ncip\CheckOutResponse', $response);
		$this->assertFalse($response->success);
		$this->assertEquals('Item does not circulate', $response->error);
		$this->assertEquals('LTID:Finnes ikke', $response->errorDetails);
	}

	public function testParseNullResponse() {
		$response = new CheckOutResponse(null);

		$this->assertInstanceOf('Scriptotek\Ncip\CheckOutResponse', $response);
	}

	public function testXmlSuccess()
	{
		$response = new CheckOutResponse;
		$response->userId = 'ex0000001';
		$response->itemId = '13k115558';
		$response->userAgencyId = 'x';
		$response->itemAgencyId = 'y';
		$response->dateDue = new \DateTime('2014-12-12T00:00:00+02:00');
		$response->success = true;
		$xml = $response->xml();

		$this->assertContains('<ns1:ItemIdentifierValue>13k115558</ns1:ItemIdentifierValue>', $xml);
		$this->assertContains('<ns1:DateDue>2014-12-12T00:00:00+0200</ns1:DateDue>', $xml);

		$response = new CheckOutResponse(new QuiteSimpleXMLElement($xml));
		$this->assertTrue($response->success);
	}

	public function testXmlFailure()
	{
		$response = new CheckOutResponse;
		$response->success = false;
		$response->error = 'Some error';
		$xml = $response->xml();

		$this->assertNotContains('<ns1:ItemIdentifierValue>13k115558</ns1:ItemIdentifierValue>', $xml);
		$this->assertContains('<ns1:ProblemType>Some error</ns1:ProblemType>', $xml);

		$response = new CheckOutResponse(new QuiteSimpleXMLElement($xml));
		$this->assertFalse($response->success);
	}

}
