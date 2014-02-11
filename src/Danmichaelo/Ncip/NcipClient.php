<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

use Danmichaelo\QuiteSimpleXMLElement\InvalidXMLException;

class NcipClient extends NcipService {

	protected $connector;

	/**
	 * Create a new Ncip client
	 *
	 * @param  string  $url
	 * @param  array   $options
	 * @return void
	 */
	public function __construct(NcipConnector $connector)
	{
		$this->connector = $connector;
		parent::__construct();
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
			throw new InvalidNcipResponseException(
				'Invalid response received from the NCIP service ' . '"' .
				$this->connector->url . '". Did you configure it correctly?'
			);
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
		$request = new CheckOutRequest($this->connector->agency_id, $user_id, $item_id);
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
		$request = new CheckInRequest($this->connector->agency_id, $item_id);
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
		$request = new RenewRequest($user_id, $item_id);
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
		$request = new ItemRequest($item_id);
		$response = $this->post($request);
		return new ItemResponse($response);
	}

}
