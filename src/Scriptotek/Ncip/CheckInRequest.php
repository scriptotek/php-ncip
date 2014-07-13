<?php namespace Scriptotek\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

class CheckInRequest extends Request implements RequestInterface {

	public $agencyId;
	public $itemId;

	/**
	 * Create a new Ncip checkin request
	 *
	 * @param  string  $agencyId
	 * @param  string  $itemId
	 * @return void
	 */
	public function __construct($agencyId, $itemId)
	{
		$this->agencyId = $agencyId;
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
				<ns1:CheckInItem>
					<ns1:ItemId>
					   <ns1:AgencyId>' . $this->agencyId . '</ns1:AgencyId>
					   <ns1:ItemIdentifierValue>' . $this->itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:CheckInItem>
			</ns1:NCIPMessage>';
	}

}