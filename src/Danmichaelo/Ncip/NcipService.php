<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class NcipService {

	protected $agency_id;
	protected $namespaces;

	/**
	 * Create a new Ncip server
	 *
	 * @param  array   $options
	 * @return void
	 */
	public function __construct($options = array())
	{
		if (isset($options['agency_id'])) {
			$this->agency_id = $options['agency_id'];
		} else {
			$this->agency_id = Config::get('ncip::agency_id');
			// if (class_exists('Config')) {
			// 	$this->agency_id = Config::get('ncip::agency_id');
			// } else {
			// 	throw new \Exception('No agency_id set');
			// }
		}

		$this->namespaces = array_get($options, 'namespaces',
			array('ns1' => 'http://www.niso.org/2008/ncip'));
	}

	/**
	 * Parses an XML-formatted NCIP request or response
	 * Throws Danmichaelo\QuiteSimpleXMLElement\InvalidXMLException on failure
	 *
	 * @param  string   $xml
	 * @return QuiteSimpleXMLElement
	 */
	public function parseXml($xml)
	{
		if (is_null($xml)) {
			return null;
		}
		$xml = new QuiteSimpleXMLElement($xml);
		$xml->registerXPathNamespaces($this->namespaces);
		return $xml;
	}

}