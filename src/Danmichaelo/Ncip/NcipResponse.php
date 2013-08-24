<?php namespace Danmichaelo\Ncip;


interface NcipResponse {

	/**
	 * Create a new Ncip response
	 *
	 * @param  CustomXMLElement  $dom
	 * @return void
	 */
	public function __construct($dom);


}
