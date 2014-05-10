<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

class UserRequest extends Request implements RequestInterface {

	public $userId;

	/**
	 * Create a new Ncip user request
	 *
	 * @param  string  $userId
	 * @return void
	 */
	public function __construct($userId)
	{
		$this->userId = $userId;
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
			<ns1:LookupUser>
				<ns1:UserId>
					<ns1:UserIdentifierValue>' . $this->userId . '</ns1:UserIdentifierValue>
				</ns1:UserId>
				<ns1:LoanedItemsDesired/>
				<ns1:RequestedItemsDesired/>
			</ns1:LookupUser>
		</ns1:NCIPMessage>';
	}

}