<?php namespace Danmichaelo\Ncip;

class Response {

	protected $dom;

	public function parseDateTime($datestring) {
		return \DateTime::createFromFormat('Y-m-d?H:i:s.ue', $datestring);
	}

}
