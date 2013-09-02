<?php namespace Danmichaelo\Ncip;

use Danmichaelo\CustomXMLElement\CustomXMLElement;


class ItemResponseTest extends \PHPUnit_Framework_TestCase {

	protected $dummy_response_onloan = '
		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
		   <ns1:LookupItemResponse>
		      <ns1:ItemId>
		         <ns1:AgencyId>q</ns1:AgencyId>
		         <ns1:ItemIdentifierValue>xxxxxxxxx</ns1:ItemIdentifierValue>
		      </ns1:ItemId>
		      <ns1:DateRecalled>2013-09-30T19:32:39.937+02:00</ns1:DateRecalled>
		      <ns1:ItemOptionalFields>
		         <ns1:BibliographicDescription>
		            <ns1:Author>Gell-Mann, Murray</ns1:Author>
		            <ns1:BibliographicRecordId>
		               <ns1:BibliographicRecordIdentifier>952245078</ns1:BibliographicRecordIdentifier>
		               <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
		            </ns1:BibliographicRecordId>
		            <ns1:Edition/>
		            <ns1:Pagination>XVIII, 392 s., ill.</ns1:Pagination>
		            <ns1:PublicationDate>1995</ns1:PublicationDate>
		            <ns1:Publisher>Abacus</ns1:Publisher>
		            <ns1:Title>The quark and the jaguar : adventures in the simple and the complex</ns1:Title>
		            <ns1:Language>eng</ns1:Language>
		            <ns1:MediumType>Book</ns1:MediumType>
		         </ns1:BibliographicDescription>
		         <ns1:ItemUseRestrictionType>Term Loan</ns1:ItemUseRestrictionType>
		         <ns1:CirculationStatus>On Loan</ns1:CirculationStatus>
		         <ns1:ItemDescription>
		            <ns1:CallNumber>1.6 GEL</ns1:CallNumber>
		            <ns1:HoldingsInformation>
		               <ns1:UnstructuredHoldingsData/>
		            </ns1:HoldingsInformation>
		            <ns1:NumberOfPieces>1</ns1:NumberOfPieces>
		         </ns1:ItemDescription>
		         <ns1:Location>
		            <ns1:LocationType>Permanent Location</ns1:LocationType>
		            <ns1:LocationName>
		               <ns1:LocationNameInstance>
		                  <ns1:LocationNameLevel>1</ns1:LocationNameLevel>
		                  <ns1:LocationNameValue>UREAL</ns1:LocationNameValue>
		               </ns1:LocationNameInstance>
		            </ns1:LocationName>
		         </ns1:Location>
		      </ns1:ItemOptionalFields>
		      <ns1:Ext>
		         <ns1:UserOptionalFields>
		            <ns1:UserLanguage>eng</ns1:UserLanguage>
		         </ns1:UserOptionalFields>
		      </ns1:Ext>
		   </ns1:LookupItemResponse>
		</ns1:NCIPMessage>
 	  ';

	protected $dummy_response_available = '
		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
		   <ns1:LookupItemResponse>
		      <ns1:ItemId>
		         <ns1:AgencyId>q</ns1:AgencyId>
		         <ns1:ItemIdentifierValue>xxxxxxxxx</ns1:ItemIdentifierValue>
		      </ns1:ItemId>
		      <ns1:ItemOptionalFields>
		         <ns1:BibliographicDescription>
		            <ns1:Author>Gell-Mann, Murray</ns1:Author>
		            <ns1:BibliographicRecordId>
		               <ns1:BibliographicRecordIdentifier>952245078</ns1:BibliographicRecordIdentifier>
		               <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
		            </ns1:BibliographicRecordId>
		            <ns1:Edition/>
		            <ns1:Pagination>XVIII, 392 s., ill.</ns1:Pagination>
		            <ns1:PublicationDate>1995</ns1:PublicationDate>
		            <ns1:Publisher>Abacus</ns1:Publisher>
		            <ns1:Title>The quark and the jaguar : adventures in the simple and the complex</ns1:Title>
		            <ns1:Language>eng</ns1:Language>
		            <ns1:MediumType>Book</ns1:MediumType>
		         </ns1:BibliographicDescription>
		         <ns1:ItemUseRestrictionType>Term Loan</ns1:ItemUseRestrictionType>
		         <ns1:CirculationStatus>Available On Shelf</ns1:CirculationStatus>
		         <ns1:ItemDescription>
		            <ns1:CallNumber>1.6 GEL</ns1:CallNumber>
		            <ns1:HoldingsInformation>
		               <ns1:UnstructuredHoldingsData/>
		            </ns1:HoldingsInformation>
		            <ns1:NumberOfPieces>1</ns1:NumberOfPieces>
		         </ns1:ItemDescription>
		         <ns1:Location>
		            <ns1:LocationType>Permanent Location</ns1:LocationType>
		            <ns1:LocationName>
		               <ns1:LocationNameInstance>
		                  <ns1:LocationNameLevel>1</ns1:LocationNameLevel>
		                  <ns1:LocationNameValue>UREAL</ns1:LocationNameValue>
		               </ns1:LocationNameInstance>
		            </ns1:LocationName>
		         </ns1:Location>
		      </ns1:ItemOptionalFields>
		      <ns1:Ext>
		         <ns1:UserOptionalFields>
		            <ns1:UserLanguage>eng</ns1:UserLanguage>
		         </ns1:UserOptionalFields>
		      </ns1:Ext>
		   </ns1:LookupItemResponse>
		</ns1:NCIPMessage>
 	  ';

	public function testOnLoanResponse() {
		$xml = new CustomXMLElement($this->dummy_response_onloan);
		$response = new ItemResponse($xml);
		$date1 = new \DateTime('2013-09-30T19:32:39+02:00');

		$this->assertInstanceOf('Danmichaelo\Ncip\ItemResponse', $response);
		$this->assertTrue($response->exists);
		$this->assertEquals('q', $response->agencyId);
		$this->assertEquals('xxxxxxxxx', $response->itemId);
		$this->assertEquals('Gell-Mann, Murray', $response->bibliographic['author']);
		$this->assertEquals('The quark and the jaguar : adventures in the simple and the complex', $response->bibliographic['title']);
		$this->assertTrue($response->onLoan);
		$this->assertEquals($response->dateRecalled->getTimestamp(), $date1->getTimestamp());
	}

	public function testAvailableResponse() {
		$xml = new CustomXMLElement($this->dummy_response_available);
		$response = new ItemResponse($xml);

		$this->assertInstanceOf('Danmichaelo\Ncip\ItemResponse', $response);
		$this->assertTrue($response->exists);
		$this->assertEquals('q', $response->agencyId);
		$this->assertEquals('xxxxxxxxx', $response->itemId);
		$this->assertFalse($response->onLoan);
		$this->assertNull($response->dateRecalled);
	}

}
