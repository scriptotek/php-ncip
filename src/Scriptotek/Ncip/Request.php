<?php namespace Scriptotek\Ncip;

class Request implements RequestInterface {

	/**
	 * Check if Request is of a specific class
	 *
	 * @return bool
	 */
	public function is($kind)
	{
		$classes = array(
			'LookupUser' => 'Scriptotek\Ncip\UserRequest',
			'LookupItem' => 'Scriptotek\Ncip\ItemRequest',
			'CheckOutItem' => 'Scriptotek\Ncip\CheckOutRequest',
			'CheckInItem' => 'Scriptotek\Ncip\CheckInRequest',
			'RenewItem' => 'Scriptotek\Ncip\RenewRequest',
		);
		return $this instanceof $classes[$kind];
	}

	/**
	 * Return XML representation of the request
	 *
	 * @return string
	 */
	public function xml()
	{
		return '';
	}
}
