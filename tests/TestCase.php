<?php namespace Scriptotek\Ncip\Test;

use Carbon\Carbon;
use Scriptotek\Ncip\CheckOutResponse;
use Scriptotek\Ncip\UserResponse;

class TestCase extends \PHPUnit_Framework_TestCase {

	public function randomString($length = 10) {
		return substr(str_shuffle(md5(time())), 0, $length);
	}

	public function loanedItems($n = 4)
	{
		$lst = array();
		for ($i=0; $i < $n; $i++) { 
			$lst[] = array(
				'id' => $this->randomString(8),
				'reminderLevel' => 2,
				'dateDue' => Carbon::now(),
				'title' => $this->randomString(20)
			);
		}
		return $lst;
	}

	public function dummyCheckoutResponse($options = array())
	{
		$res = new CheckOutResponse;
		$res->success = array_get($options, 'success', true);
		$res->userId = array_get($options, 'userId', $this->randomString(8));
		$res->itemId = array_get($options, 'itemId', $this->randomString(8));
		$res->userAgencyId = array_get($options, 'userAgencyId', $this->randomString(2));
		$res->itemAgencyId = array_get($options, 'itemAgencyId', $this->randomString(2));
		$res->dateDue = array_get($options, 'dateDue', Carbon::now());
		return $res;
	}

	public function dummyUserResponse($options = array())
	{
		$res = new UserResponse;
		$res->success = array_get($options, 'success', true);
		$res->exists = array_get($options, 'exists', true);
		$res->userId = array_get($options, 'userId', $this->randomString(8));
		$res->agencyId = array_get($options, 'agencyId', $this->randomString(2));
		$res->firstName = array_get($options, 'firstName', $this->randomString(12));
		$res->lastName = array_get($options, 'lastName', $this->randomString(8));
		$res->phone = array_get($options, 'phone', $this->randomString(8));
		$res->email = array_get($options, 'email', $this->randomString(12));
		$res->lang = array_get($options, 'lang', $this->randomString(2));

		$res->dateDue = array_get($options, 'dateDue', Carbon::now());

		$res->loanedItems = array_get($options, 'loanedItems', $this->loanedItems(4));

		return $res;
	}

}
