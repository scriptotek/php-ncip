<?php namespace Danmichaelo\Ncip;

interface NcipConnectorInterface {

	/**
	 * Post xml document to the NCIP service
	 *
	 * @param  string  $request
	 * @return CustomXMLElement
	 */
	public function post($request);

}
