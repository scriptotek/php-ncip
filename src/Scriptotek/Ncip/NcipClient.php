<?php namespace Scriptotek\Ncip;
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
	 * @param  NcipConnector  $connector
	 * @param  EventEmitter   $emitter
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
		$this->emit('message.send', array($request->xml()));
		$response = $this->connector->post($request);
		$this->emit('message.recv', array($response));

		try {
			return $this->parseXml($response);
		} catch (InvalidXMLException $e) {
			throw new InvalidNcipResponseException(
				'Invalid response received from the NCIP service "' .
				$this->connector->url . '": ' . $response
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
		$this->emit('request.user', array($user_id));
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
		$this->emit('request.checkout', array($user_id, $item_id));
		$response = $this->post($request);
		return new CheckOutResponse($response);
	}

	/**
	 * Check in an item
	 *
	 * @param  string  $item_id
	 * @return CheckInResponse
	 */
	public function checkInItem($item_id)
	{
		$request = new CheckInRequest($this->connector->agency_id, $item_id);
		$this->emit('request.checkin', array($item_id));
		$response = $this->post($request);
		return new CheckInResponse($response);
	}

	/**
	 * Renew an item for a user
	 *
	 * @param  string  $user_id
	 * @param  string  $item_id
	 * @return RenewResponse
	 */
	public function renewItem($user_id, $item_id)
	{
		$request = new RenewRequest($user_id, $item_id);
		$this->emit('request.renew', array($user_id, $item_id));
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
		$this->emit('request.item', array($item_id));
		$response = $this->post($request);
		return new ItemResponse($response);
	}

}
