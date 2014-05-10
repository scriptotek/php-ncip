<?php namespace Danmichaelo\Ncip;

class Request implements RequestInterface {

	/**
	 * Check if Request is of a specific class
	 *
	 * @return bool
	 */
	public function is($kind)
	{
		$classes = array(
			'LookupUser' => 'Danmichaelo\Ncip\UserRequest',
			'LookupItem' => 'Danmichaelo\Ncip\ItemRequest',
			'CheckOutItem' => 'Danmichaelo\Ncip\CheckOutRequest',
			'CheckInItem' => 'Danmichaelo\Ncip\CheckInRequest',
			'RenewItem' => 'Danmichaelo\Ncip\RenewRequest',
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
