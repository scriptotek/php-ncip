<?php namespace Scriptotek\Ncip;


interface ResponseInterface {

	/**
	 * Create a new Ncip response
	 *
	 * @param  CustomXMLElement  $dom
	 * @return void
	 */
	public function __construct($dom);


}
