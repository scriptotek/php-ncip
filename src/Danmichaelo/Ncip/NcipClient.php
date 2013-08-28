<?php namespace Danmichaelo\Ncip;
/* 
 * (c) Dan Michael O. Heggø (2013)
 * 
 * Basic Ncip library. This class currently only implements 
 * a small subset of the NCIP services.
 */

class NcipClient {

	protected $agency_id;
	protected $connector;

	/**
	 * Create a new Ncip client
	 *
	 * @param  string  $url
	 * @param  array   $options
	 * @return void
	 */
	public function __construct(NcipConnector $connector = null, $options = array())
	{
		$this->agency_id = array_get($options, 'agency_id', Config::get('ncip::agency_id'));
		$this->connector = $connector ?: new NcipConnector;
	}

	/**
	 * Lookup user information from user id
	 *
	 * @param  string  $user_id
	 * @return UserResponse
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

		$response = $this->connector->post($request);
		return new UserResponse($response);
	}

	/**
	 * Check out an item to a user
	 *
	 * @param  string  $user_id
	 * @param  string  $item_id
	 * @return CheckoutResponse
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

		$response = $this->connector->post($request);
		return new CheckoutResponse($response);
	}

	/**
	 * Check in an item
	 * 
	 * Example response:
	 *
	 *		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
	 *		   <ns1:CheckInItemResponse>
	 *			  <ns1:ItemId>
	 *				 <ns1:AgencyId>k</ns1:AgencyId>
	 *				 <ns1:ItemIdentifierValue>13k040189</ns1:ItemIdentifierValue>
	 *			  </ns1:ItemId>
	 *			  <ns1:ItemOptionalFields>
	 *				 <ns1:BibliographicDescription>
	 *					<ns1:Author>DuCharme, Bob</ns1:Author>
	 *					<ns1:BibliographicRecordId>
	 *					   <ns1:BibliographicRecordIdentifier>11447981x</ns1:BibliographicRecordIdentifier>
	 *					   <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
	 *					</ns1:BibliographicRecordId>
	 *					<ns1:Edition/>
	 *					<ns1:Pagination>XIII, 235 s., ill.</ns1:Pagination>
	 *					<ns1:PublicationDate>2011</ns1:PublicationDate>
	 *					<ns1:Publisher>O'Reilly</ns1:Publisher>
	 *					<ns1:Title>Learning SPARQL : querying and updating with SPARQL 1.1</ns1:Title>
	 *					<ns1:Language>eng</ns1:Language>
	 *					<ns1:MediumType>Book</ns1:MediumType>
	 *				 </ns1:BibliographicDescription>
	 *			  </ns1:ItemOptionalFields>
	 *			  <ns1:Ext>
	 *				 <ns1:UserOptionalFields>
	 *					<ns1:UserLanguage>eng</ns1:UserLanguage>
	 *				 </ns1:UserOptionalFields>
	 *			  </ns1:Ext>
	 *		   </ns1:CheckInItemResponse>
	 *		</ns1:NCIPMessage>
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

		$response = $this->connector->post($request);
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

	/**
	 * Lookup item information from item id
	 *
	 * @param  string  $item_id
	 * @return array
	 */
	public function lookupItem($item_id)
	{
		$request = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
			<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip" ns1:version="http://www.niso.org/schemas/ncip/v2_01/ncip_v2_01.xsd">
				<ns1:LookupItem>
					<ns1:ItemId>
					   <ns1:ItemIdentifierType>Accession Number</ns1:ItemIdentifierType>
					   <ns1:ItemIdentifierValue>' . $item_id . '</ns1:ItemIdentifierValue>
					</ns1:ItemId>
				</ns1:LookupItem>
			</ns1:NCIPMessage>';

		$response = $this->connector->post($request);
		$response = $response->first('/ns1:NCIPMessage/ns1:LookupItemResponse');

		if ($response->first('ns1:Problem')) {
			$o = array(
				'exists' => false,
				'error' => $response->text('ns1:Problem/ns1:ProblemDetail')
			);
		} else {
			$uinfo = $response->first('ns1:UserOptionalFields');
			$o = array(
				'exists' => true,
				'dateRecalled' => $response->text('ns1:DateRecalled'),
			);

			$oinfo = $response->first('ns1:ItemOptionalFields');
			$o['circulationStatus'] = $oinfo->text('ns1:CirculationStatus');

			$o['title'] = $oinfo->text('ns1:BibliographicDescription/ns1:Title');
			$o['author'] = $oinfo->text('ns1:BibliographicDescription/ns1:Author');
			$o['publicationDate'] = $oinfo->text('ns1:BibliographicDescription/ns1:PublicationDate');
			$o['publisher'] = $oinfo->text('ns1:BibliographicDescription/ns1:Publisher');

		}

		return $o;
	}
}