<?php namespace Danmichaelo\Ncip;

interface NcipClientInterface {

	/**
	 * Create a new Ncip client
	 *
	 * @param  string  $url
	 * @param  array   $options
	 * @return void
	 */
	public function __construct(NcipConnector $connector, $options = array());

	/**
	 * Lookup user information from user id
	 *
	 * @param  string  $user_id
	 * @return array
	 */
	public function lookupUser($user_id);

	/**
	 * Check out an item to a user
	 *
	 * @param  string  $user_id
	 * @param  string  $item_id
	 * @return array
	 */
	public function checkOutItem($user_id, $item_id);

	/**
	 * Check in an item
	 *
	 * @param  string  $item_id
	 * @return array
	 */
	public function checkInItem($item_id);

	/**
	 * Lookup item information from item id
	 *
	 * @param  string  $item_id
	 * @return array
	 */
	public function lookupItem($item_id);

}
