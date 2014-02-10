<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

class NcipServer extends NcipService {

	/**
	 * Create a new Ncip server
	 *
	 * @param  string   $agency_id
	 * @return void
	 */
	public function __construct($agency_id)
	{
		parent::__construct($agency_id);
	}

	public function parseRequest($xml)
	{
		$x = $this->parseXml($xml);
		$kids = $x->first('/ns1:NCIPMessage')->children('ns1');
		if (count($kids) !== 1) {
			throw new InvalidNcipRequestException('No NCIP message received');
		}
		$msg = $kids[0];
		$requestType = $msg->getName();
		$request = null;

		switch ($requestType) {

			case 'LookupUser':
				$userId = $msg->text('ns1:UserId/ns1:UserIdentifierValue');
				$request = new UserRequest($userId);
				break;

			case 'RenewItem':
				$userId = $msg->text('ns1:AuthenticationInput/ns1:AuthenticationInputData');
				$itemId = $msg->text('ns1:ItemId/ns1:ItemIdentifierValue');
				$request = new RenewRequest($userId, $itemId);
				break;

			case 'CheckOutItem':
				$userId = $msg->text('ns1:UserId/ns1:UserIdentifierValue');
				$agencyId = $msg->text('ns1:ItemId/ns1:AgencyId');
				$itemId = $msg->text('ns1:ItemId/ns1:ItemIdentifierValue');
				$request = new CheckOutRequest($agencyId, $userId, $itemId);
				break;

			case 'CheckInItem':
				$agencyId = $msg->text('ns1:ItemId/ns1:AgencyId');
				$itemId = $msg->text('ns1:ItemId/ns1:ItemIdentifierValue');
				$request = new CheckInRequest($agencyId, $itemId);
				break;

			case 'LookupItem':
				$itemId = $x->text('ns1:ItemId/ns1:ItemIdentifierValue');
				$request = new ItemRequest($itemId);
				break;

			default:
				throw new InvalidNcipRequestException('Unknown NCIP request "' . $requestType . '" received');

		}
		return $request;
	}


}
