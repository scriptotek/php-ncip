<?php namespace Scriptotek\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 *
 * Example response:
 *
 *		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 *		  <ns1:LookupItemResponse>
 *		     <ns1:ItemId>
 *		        <ns1:AgencyId>k</ns1:AgencyId>
 *		        <ns1:ItemIdentifierValue>94nf00228</ns1:ItemIdentifierValue>
 *		     </ns1:ItemId>
 *		     <ns1:ItemOptionalFields>
 *		        <ns1:BibliographicDescription>
 *		           <ns1:Author>Gell-Mann, Murray</ns1:Author>
 *		           <ns1:BibliographicRecordId>
 *		              <ns1:BibliographicRecordIdentifier>952245078</ns1:BibliographicRecordIdentifier>
 *		              <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
 *		           </ns1:BibliographicRecordId>
 *		           <ns1:Edition/>
 *		           <ns1:Pagination>XVIII, 392 s., ill.</ns1:Pagination>
 *		           <ns1:PublicationDate>1995</ns1:PublicationDate>
 *		           <ns1:Publisher>Abacus</ns1:Publisher>
 *		           <ns1:Title>The quark and the jaguar : adventures in the simple and the complex</ns1:Title>
 *		           <ns1:Language>eng</ns1:Language>
 *		           <ns1:MediumType>Book</ns1:MediumType>
 *		        </ns1:BibliographicDescription>
 *		        <ns1:ItemUseRestrictionType>Term Loan</ns1:ItemUseRestrictionType>
 *		        <ns1:CirculationStatus>Available On Shelf</ns1:CirculationStatus>
 *		        <ns1:ItemDescription>
 *		           <ns1:CallNumber>1.6 GEL</ns1:CallNumber>
 *		           <ns1:HoldingsInformation>
 *		              <ns1:UnstructuredHoldingsData/>
 *		           </ns1:HoldingsInformation>
 *		           <ns1:NumberOfPieces>1</ns1:NumberOfPieces>
 *		        </ns1:ItemDescription>
 *		        <ns1:Location>
 *		           <ns1:LocationType>Permanent Location</ns1:LocationType>
 *		           <ns1:LocationName>
 *		              <ns1:LocationNameInstance>
 *		                 <ns1:LocationNameLevel>1</ns1:LocationNameLevel>
 *		                 <ns1:LocationNameValue>UREAL</ns1:LocationNameValue>
 *		              </ns1:LocationNameInstance>
 *		           </ns1:LocationName>
 *		        </ns1:Location>
 *		     </ns1:ItemOptionalFields>
 *		     <ns1:Ext>
 *		        <ns1:UserOptionalFields>
 *		           <ns1:UserLanguage>eng</ns1:UserLanguage>
 *		        </ns1:UserOptionalFields>
 *		     </ns1:Ext>
 *		  </ns1:LookupItemResponse>
 *		</ns1:NCIPMessage>
 */

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class ItemResponse extends Response {

	public $exists = false;
	public $agencyId;
	public $itemId;
	public $dateRecalled;
	public $circulationStatus;
	public $onLoan;
	public $bibliographic;
	public $error;
	public $errorDetails;

	protected $dom;

	/**
	 * Create a new Ncip user response
	 *
	 * @param  QuiteSimpleXMLElement  $dom
	 * @return void
	 */
	public function __construct(QuiteSimpleXMLElement $dom = null)
	{
		if (is_null($dom)) return;
		parent::__construct($dom->first('/ns1:NCIPMessage/ns1:LookupItemResponse'));

		if ($this->success) {

			$this->exists = true; // not really needed when we have 'success'
			$this->itemId = $this->dom->text('ns1:ItemId/ns1:ItemIdentifierValue');
			$this->agencyId = $this->dom->text('ns1:ItemId/ns1:AgencyId');
			$this->dateRecalled = $this->dom->text('ns1:DateRecalled')
				? $this->parseDateTime($this->dom->text('ns1:DateRecalled'))
				: null;
			$nfo = $this->dom->first('ns1:ItemOptionalFields');

			$this->circulationStatus = $nfo->text('ns1:CirculationStatus');
			$this->onLoan = ($this->circulationStatus === 'On Loan');

			$this->bibliographic = $this->parseBibliographicDescription($nfo->first('ns1:BibliographicDescription'));
			//$this->callNumber = $nfo->text('ns1:ItemDescription/ns1:CallNumber');
			//$this->location = $nfo->text('ns1:Location/ns1:LocationName/ns1:LocationNameInstance/ns1:LocationNameValue');

		}
	}

}
