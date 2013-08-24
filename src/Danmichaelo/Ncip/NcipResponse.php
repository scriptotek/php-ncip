<?php namespace Danmichaelo\Ncip;

class NcipResponse {

	protected $dom;

	public function parseDateTime($datestring) {
		return \DateTime::createFromFormat('Y-m-d?H:i:s.ue', $datestring);
	}

}
