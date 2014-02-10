<?php namespace Danmichaelo\Ncip;

class Request {

	public function is($kind)
	{
		if ($kind == 'LookupUser' && $this instanceof \Danmichaelo\Ncip\UserRequest) {
			return true;
		}
		if ($kind == 'LookupItem' && $this instanceof \Danmichaelo\Ncip\ItemRequest) {
			return true;
		}
		if ($kind == 'CheckOutItem' && $this instanceof \Danmichaelo\Ncip\CheckOutItem) {
			return true;
		}
		if ($kind == 'CheckInItem' && $this instanceof \Danmichaelo\Ncip\CheckInItem) {
			return true;
		}
		if ($kind == 'RenewItem' && $this instanceof \Danmichaelo\Ncip\RenewRequest) {
			return true;
		}
		return false;
	}
}
