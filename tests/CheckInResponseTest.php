<?php namespace Danmichaelo\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class CheckInResponseTest extends \PHPUnit_Framework_TestCase {

	protected $dummy_response_success = '
 		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 		   <ns1:CheckInItemResponse>
 			  <ns1:ItemId>
 				 <ns1:AgencyId>q</ns1:AgencyId>
 				 <ns1:ItemIdentifierValue>xxxxxxxxx</ns1:ItemIdentifierValue>
 			  </ns1:ItemId>
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
 		   </ns1:CheckInItemResponse>
 		</ns1:NCIPMessage>';

	public function testParseDummySuccessResponse() {
		$dummy_response = new QuiteSimpleXMLElement($this->dummy_response_success);
		$response = new CheckInResponse($dummy_response);

		$this->assertInstanceOf('Danmichaelo\Ncip\CheckInResponse', $response);
		$this->assertTrue($response->success);
	}

}
