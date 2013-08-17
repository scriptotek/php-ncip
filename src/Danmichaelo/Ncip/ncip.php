<?php
/* 
 * (c) Dan Michael O. Heggø (2013)
 * 
 * Basic Ncip library. This class currently only implements 
 * a small subset of the NCIP services.
 */

namespace Danmichaelo\Ncip;

use Danmichaelo\CustomXMLElement\CustomXMLElement;

class Ncip {

	protected $url;
	protected $agency_id;
	protected $user_agent;
	protected $namespaces;

	/**
	 * Create a new Ncip connector
	 *
	 * @param  string  $url
	 * @param  array   $options
	 * @return void
	 */
	public function __construct($url, $agency_id, $options = array())
	{
		$this->url = $url;

		// http://laravel.com/api/function-array_get.html
		// Illuminate/Support/helpers.php

		$this->user_agent = array_get($options, 'user_agent', null);

		$this->namespaces = array_get($options, 'namespaces', 
			array('ns1' => 'http://www.niso.org/2008/ncip'));

		$this->agency_id = $agency_id;
	}

	/**
	 * Posts an xml document to the NCIP service
	 *
	 * @param  string  $request
	 * @return CustomXMLElement
	 */
	private function post($request) 
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

		$xml = new CustomXMLElement($response);
		$xml->registerXPathNamespaces($this->namespaces);

		return $xml;
	}

	/**
	 * Posts an xml document to the NCIP service
	 *
	 * @param  string  $user_id
	 * @return array
	 */
	public function lookupUser($user_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
			<ns1:LookupUser>
				<ns1:UserId>
					<ns1:UserIdentifierValue>' . $user_id . '</ns1:UserIdentifierValue>
				</ns1:UserId>
				<ns1:LoanedItemsDesired/>
				<ns1:RequestedItemsDesired/>
			</ns1:LookupUser>
		</ns1:NCIPMessage>';

		$response = $this->post($request);
		$response = $response->first('/ns1:NCIPMessage/ns1:LookupUserResponse');

		if ($response->first('ns1:Problem')) {
			$o = array(
				'exists' => false
			);
		} else {
			$uinfo = $response->first('ns1:UserOptionalFields');
			$o = array(
				'exists' => true,
				'agencyId' => $response->text('ns1:UserId/ns1:AgencyId'),
				'userid' => $response->text('ns1:UserId/ns1:UserIdentifierValue'),
				'firstname' => $uinfo->text('ns1:NameInformation/ns1:PersonalNameInformation/ns1:StructuredPersonalUserName/ns1:GivenName'),
				'lastname' => $uinfo->text('ns1:NameInformation/ns1:PersonalNameInformation/ns1:StructuredPersonalUserName/ns1:Surname'),
				'lang' => $uinfo->text('ns1:UserLanguage'), // Gir "eng" for alle foreløpig
			);
			foreach ($uinfo->xpath('ns1:UserAddressInformation') as $adrinfo) {
				if ($adrinfo->text('ns1:UserAddressRoleType') == 'mailto') {
					$o['email'] = $adrinfo->text('ns1:ElectronicAddress/ns1:ElectronicAddressData');
				} else if ($adrinfo->text('ns1:UserAddressRoleType') == 'sms') {
					$o['phone'] = $adrinfo->text('ns1:ElectronicAddress/ns1:ElectronicAddressData');
				}
			}

		}

		return $o;
	}

	/**
	 * Posts an xml document to the NCIP service
	 *
	 * @param  string  $user_id
	 * @param  string  $item_id
	 * @return array
	 */
	public function checkOutItem($user_id, $item_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
			    <ns1:CheckOutItem>
			        <ns1:UserId>
			            <ns1:UserIdentifierValue>' . $user_id . '</ns1:UserIdentifierValue>
			        </ns1:UserId>
			        <ns1:ItemId>
			           <ns1:AgencyId>' . $this->agency_id . '</ns1:AgencyId>
			           <ns1:ItemIdentifierValue>' . $item_id . '</ns1:ItemIdentifierValue>
			        </ns1:ItemId>
			    </ns1:CheckOutItem>
			</ns1:NCIPMessage>';

		$response = $this->post($request);
		$response = $response->first('/ns1:NCIPMessage/ns1:CheckOutItemResponse');

		if ($response->first('ns1:Problem')) {
			$o = array(
				'success' => false,
				'error' => $response->text('ns1:Problem/ns1:ProblemDetail')
			);
		} else {
			$o = array(
				'success' => true
			);
		}

		return $o;
	}

	/**
	 * Posts an xml document to the NCIP service
	 *
	 * @param  string  $item_id
	 * @return array
	 */
	public function checkInItem($item_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
			    <ns1:CheckInItem>
			        <ns1:ItemId>
			           <ns1:AgencyId>' . $this->agency_id . '</ns1:AgencyId>
			           <ns1:ItemIdentifierValue>' . $item_id . '</ns1:ItemIdentifierValue>
			        </ns1:ItemId>
			    </ns1:CheckInItem>
			</ns1:NCIPMessage>';

		$response = $this->post($request);
		$response = $response->first('/ns1:NCIPMessage/ns1:CheckInItemResponse');

		if ($response->first('ns1:Problem')) {
			$o = array(
				'success' => false,
				'error' => $response->text('ns1:Problem/ns1:ProblemDetail')
			);
		} else {
			$o = array(
				'success' => true
			);
		}

		return $o;
	}
}
