<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

class CheckOutRequest extends Request implements RequestInterface {

	public $agencyId;
	public $userId;
	public $itemId;

	/**
	 * Create a new Ncip checkout request
	 *
	 * @param  string  $agencyId
	 * @param  string  $userId
	 * @param  string  $itemId
	 * @return void
	 */
	public function __construct($agencyId, $userId, $itemId)
	{
		$this->agencyId = $agencyId;
		$this->userId = $userId;
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
				<ns1:CheckOutItem>
					<ns1:UserId>
						<ns1:UserIdentifierValue>' . $this->userId . '</ns1:UserIdentifierValue>
					</ns1:UserId>
					<ns1:ItemId>
					   <ns1:AgencyId>' . $this->agencyId . '</ns1:AgencyId>
					   <ns1:ItemIdentifierValue>' . $this->itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:CheckOutItem>
			</ns1:NCIPMessage>';
	}

}