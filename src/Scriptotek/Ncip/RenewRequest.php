<?php namespace Scriptotek\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

class RenewRequest extends Request implements RequestInterface {

	public $userId;
	public $itemId;

	/**
	 * Create a new Ncip renew request
	 *
	 * @param  string  $userId
	 * @param  string  $itemId
	 * @return void
	 */
	public function __construct($userId, $itemId)
	{
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
				<ns1:RenewItem>
					<ns1:AuthenticationInput>
						<ns1:AuthenticationInputData>' . $this->userId . '</ns1:AuthenticationInputData>
						<ns1:AuthenticationDataFormatType>text</ns1:AuthenticationDataFormatType>
						<ns1:AuthenticationInputType>User Id</ns1:AuthenticationInputType>
					</ns1:AuthenticationInput>
					<ns1:ItemId>
					   <ns1:ItemIdentifierValue>' . $this->itemId . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:RenewItem>
			</ns1:NCIPMessage>';
	}

}