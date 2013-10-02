<?php namespace Danmichaelo\Ncip;

interface NcipConnectorInterface {

	/**
	 * Post xml document to the NCIP service
	 *
	 * @param  string  $request
	 * @return QuiteSimpleXMLElement
	 */
	public function post($request);

}
