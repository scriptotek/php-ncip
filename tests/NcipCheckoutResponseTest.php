<?php namespace Danmichaelo\Ncip;

use Danmichaelo\CustomXMLElement\CustomXMLElement;

class NcipCheckoutResponseTest extends \PHPUnit_Framework_TestCase {

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
		$dummy_response = new CustomXMLElement($this->dummy_response_success);
		$response = new NcipCheckoutResponse($dummy_response);
		$date1 = new \DateTime('2013-09-21T18:54:39+02:00');

		$this->assertInstanceOf('Danmichaelo\Ncip\NcipCheckoutResponse', $response);
		$this->assertTrue($response->success);
		$this->assertEquals($response->dueDate->getTimestamp(), $date1->getTimestamp());
	}

	public function testParseDummyFailResponse() {
		$dummy_response = new CustomXMLElement($this->dummy_response_fail);
		$response = new NcipCheckoutResponse($dummy_response);

		$this->assertInstanceOf('Danmichaelo\Ncip\NcipCheckoutResponse', $response);
		$this->assertFalse($response->success);
	}

}
