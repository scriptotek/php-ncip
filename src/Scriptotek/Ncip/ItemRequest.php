<?php namespace Scriptotek\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

class ItemRequest extends Request implements RequestInterface {

	public $itemId;

	/**
	 * Create a new Ncip item request
	 *
	 * @param  string  $itemId
	 * @return void
	 */
	public function __construct($itemId)
	{
		$this->itemId = $itemId;
	}

	/**
	 * Return XML representation of the request
	 *
	 * @return string
	 */
	public function xml()
	{
		return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:LookupItem>
					<ns1:ItemId>
					   <ns1:ItemIdentifierType>Accession Number</ns1:ItemIdentifierType>
					   <ns1:ItemIdentifierValue>' . $this->itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:LookupItem>
			</ns1:NCIPMessage>';
	}

}