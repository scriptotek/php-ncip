<?php namespace Danmichaelo\Ncip;
/* 
 * (c) Dan Michael O. Heggø (2013)
 * 
 * Basic Ncip library. This class currently only implements 
 * a small subset of the NCIP services.
 */

#use Danmichaelo\CustomXMLElement\CustomXMLElement;


class Config {

	static function get($key) {
		if (class_exists('\Config')) {
			return \Config::get($key);			
		} else {
			return null;
		}
	}

}

class NcipConnector {

	protected $url;
	protected $user_agent;
	protected $namespaces;

	/**
	 * Create a new Ncip connector
	 *
	 * @param  string  $url
	 * @param  array   $options
	 * @return void
	 */
	public function __construct($options = array())
	{
		$this->url = array_get($options, 'url', Config::get('ncip::url'));
		$this->user_agent = array_get($options, 'user_agent', Config::get('ncip::user_agent'));
		$this->namespaces = array_get($options, 'namespaces', 
			array('ns1' => 'http://www.niso.org/2008/ncip'));
	}

	/**
	 * Post xml document to the NCIP service
	 *
	 * @param  string  $request
	 * @return CustomXMLElement
	 */
	public function post($request) 
	{

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

		// TODO: Throw some smart exception if we receive invalid XML (most likely due to wrong or no URL set)
		//try {
			$xml = new CustomXMLElement($response);
		//} catch (Exception $e) {
		//  	dd("Did not receive a valid XML response from the NCIP server");
		//}

		$xml->registerXPathNamespaces($this->namespaces);

		return $xml;
	}

}