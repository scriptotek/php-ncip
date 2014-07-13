<?php namespace Scriptotek\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

class NcipConnector {

	public $url;
	public $user_agent;
	public $agency_id;

	/**
	 * Create a new Ncip connector
	 *
	 * @param  string  $url
	 * @param  string  $user_agent
	 * @param  string  $agency_id
	 * @return void
	 */
	public function __construct($url, $user_agent, $agency_id)
	{
		$this->url = $url;
		$this->user_agent = $user_agent;
		$this->agency_id = $agency_id;
	}

	/**
	 * Post xml document to the NCIP service
	 *
	 * @param  Request  $request
	 * @return string
	 */
	public function post($request)
	{
		if ($request instanceof Request) {
			$request = $request->xml();
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		if ($this->user_agent != null) {
			curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		}
		curl_setopt($ch, CURLOPT_HEADER, 0); // no headers in the output
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return instead of output
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-type: application/xml; charset=utf-8',
			));
		$response = curl_exec($ch);
		curl_close($ch);

		if (empty($response)) {
			return null;
		}

		return $response;
	}

}
