<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

use Danmichaelo\QuiteSimpleXMLElement\InvalidXMLException;

class NcipClient extends NcipService {

	protected $agency_id;
	protected $connector;
	protected $namespaces;

	/**
	 * Create a new Ncip client
	 *
	 * @param  string  $url
	 * @param  array   $options
	 * @return void
	 */
	public function __construct(NcipConnector $connector = null, $options = array())
	{
		$this->connector = $connector ?: new NcipConnector;
		parent::__construct($options);
	}

	/**
	 * Make a POST request to the NCIP server and return the response
	 *
	 * @param  Request
	 * @return Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement
	 */
	public function post(Request $request)
	{
		try {
			return $this->parseXml($this->connector->post($request));
		} catch (InvalidXMLException $e) {
			throw new InvalidNcipResponseException('Invalid response received from the NCIP service "' . $this->connector->url . '". Did you configure it correctly?');
		}
	}

	/**
	 * Lookup user information from user id
	 *
	 * @param  string  $user_id
	 * @return UserResponse
	 */
	public function lookupUser($user_id)
	{
		$request = new UserRequest($user_id);
		$response = $this->post($request);
		return new UserResponse($response);
	}

	/**
	 * Check out an item to a user
	 *
	 * @param  string  $user_id
	 * @param  string  $item_id
	 * @return CheckOutResponse
	 */
	public function checkOutItem($user_id, $item_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:CheckOutItem>
					<ns1:UserId>
						<ns1:UserIdentifierValue>' . $user_id . '</ns1:UserIdentifierValue>
					</ns1:UserId>
					<ns1:ItemId>
					   <ns1:AgencyId>' . $this->agency_id . '</ns1:AgencyId>
					   <ns1:ItemIdentifierValue>' . $item_id . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:CheckOutItem>
			</ns1:NCIPMessage>';

		$response = $this->post($request);
		return new CheckOutResponse($response);
	}

	/**
	 * Check in an item
	 *
	 * @param  string  $item_id
	 * @return array
	 */
	public function checkInItem($item_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:CheckInItem>
					<ns1:ItemId>
					   <ns1:AgencyId>' . $this->agency_id . '</ns1:AgencyId>
					   <ns1:ItemIdentifierValue>' . $item_id . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:CheckInItem>
			</ns1:NCIPMessage>';

		$response = $this->post($request);
		return new CheckInResponse($response);
	}

	/**
	 * Renew an item for a user
	 *
	 * @param  string  $user_id
	 * @param  string  $item_id
	 * @return CheckOutResponse
	 */
	public function renewItem($user_id, $item_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:RenewItem>
					<ns1:AuthenticationInput>
						<ns1:AuthenticationInputData>' . $user_id . '</ns1:AuthenticationInputData>
						<ns1:AuthenticationDataFormatType>text</ns1:AuthenticationDataFormatType>
						<ns1:AuthenticationInputType>User Id</ns1:AuthenticationInputType>
					</ns1:AuthenticationInput>
					<ns1:ItemId>
					   <ns1:ItemIdentifierValue>' . $item_id . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:RenewItem>
			</ns1:NCIPMessage>';

		$response = $this->post($request);
		return new RenewResponse($response);
	}

	/**
	 * Lookup item information from item id
	 *
	 * @param  string  $item_id
	 * @return ItemResponse
	 */
	public function lookupItem($item_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:LookupItem>
					<ns1:ItemId>
					   <ns1:ItemIdentifierType>Accession Number</ns1:ItemIdentifierType>
					   <ns1:ItemIdentifierValue>' . $item_id . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:LookupItem>
			</ns1:NCIPMessage>';

		$response = $this->post($request);
		return new ItemResponse($response);
	}

}
