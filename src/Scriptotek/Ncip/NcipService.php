<?php namespace Scriptotek\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class NcipService {

	public $namespaces;

	/**
	 * Create a new Ncip server
	 *
	 * @return void
	 */
	public function __construct()
	{

		$this->namespaces = array(
			'ns1' => 'http://www.niso.org/2008/ncip'
		);
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