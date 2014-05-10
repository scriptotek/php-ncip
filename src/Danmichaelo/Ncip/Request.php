<?php namespace Danmichaelo\Ncip;

class Request implements RequestInterface {

	/**
	 * Check if Request is of a specific class
	 *
	 * @return bool
	 */
	public function is($kind)
	{
		if ($kind == 'LookupUser' && $this instanceof \Danmichaelo\Ncip\UserRequest) {
			return true;
		}
		if ($kind == 'LookupItem' && $this instanceof \Danmichaelo\Ncip\ItemRequest) {
			return true;
		}
		if ($kind == 'CheckOutItem' && $this instanceof \Danmichaelo\Ncip\CheckOutRequest) {
			return true;
		}
		if ($kind == 'CheckInItem' && $this instanceof \Danmichaelo\Ncip\CheckInRequest) {
			return true;
		}
		if ($kind == 'RenewItem' && $this instanceof \Danmichaelo\Ncip\RenewRequest) {
			return true;
		}
		return false;
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
