<?php namespace Danmichaelo\Ncip;


interface NcipResponseInterface {

	/**
	 * Create a new Ncip response
	 *
	 * @param  CustomXMLElement  $dom
	 * @return void
	 */
	public function __construct($dom);


}
