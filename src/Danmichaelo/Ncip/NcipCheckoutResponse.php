<?php namespace Danmichaelo\Ncip;
/* 
 * (c) Dan Michael O. Heggø (2013)
 * 
 * Basic Ncip library. This class currently only implements 
 * a small subset of the NCIP services.
 *
 * Example response:
 *
 *	  <ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 *	     <ns1:CheckOutItemResponse>
 *	        <ns1:ItemId>
 *	           <ns1:AgencyId>k</ns1:AgencyId>
 *	           <ns1:ItemIdentifierValue>13k040189</ns1:ItemIdentifierValue>
 *	        </ns1:ItemId>
 *	        <ns1:UserId>
 *	           <ns1:AgencyId>k</ns1:AgencyId>
 *	           <ns1:UserIdentifierValue>xxxxxxxxxx</ns1:UserIdentifierValue>
 *	        </ns1:UserId>
 *	        <ns1:DateDue>2013-09-21T18:54:39.718+02:00</ns1:DateDue>
 *	        <ns1:ItemOptionalFields>
 *	           <ns1:BibliographicDescription>
 *	              <ns1:Author>DuCharme, Bob</ns1:Author>
 *	              <ns1:BibliographicRecordId>
 *	                 <ns1:BibliographicRecordIdentifier>11447981x</ns1:BibliographicRecordIdentifier>
 *	                 <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
 *	              </ns1:BibliographicRecordId>
 *	              <ns1:Edition/>
 *	              <ns1:Pagination>XIII, 235 s., ill.</ns1:Pagination>
 *	              <ns1:PublicationDate>2011</ns1:PublicationDate>
 *	              <ns1:Publisher>O'Reilly</ns1:Publisher>
 *	              <ns1:Title>Learning SPARQL : querying and updating with SPARQL 1.1</ns1:Title>
 *	              <ns1:Language>eng</ns1:Language>
 *	              <ns1:MediumType>Book</ns1:MediumType>
 *	           </ns1:BibliographicDescription>
 *	        </ns1:ItemOptionalFields>
 *	        <ns1:Ext>
 *	           <ns1:UserOptionalFields>
 *	              <ns1:UserLanguage>eng</ns1:UserLanguage>
 *	           </ns1:UserOptionalFields>
 *	        </ns1:Ext>
 *	     </ns1:CheckOutItemResponse>
 *	  </ns1:NCIPMessage>
 */

class NcipCheckoutResponse extends NcipResponse implements NcipResponseInterface {

	public $success;
	public $error;
	public $dueDate;

	/**
	 * Create a new Ncip user response
	 *
	 * @param  CustomXMLElement  $dom
	 * @return void
	 */
	public function __construct($dom)
	{

		$this->dom = $dom->first('/ns1:NCIPMessage/ns1:CheckOutItemResponse');

		if ($this->dom->first('ns1:Problem')) {
			$this->success = false;
			$this->error = $this->dom->text('ns1:Problem/ns1:ProblemDetail');
		} else {
			$this->success = true;
			$this->dueDate = $this->parseDateTime($this->dom->text('ns1:DateDue'));
		}

	}

}