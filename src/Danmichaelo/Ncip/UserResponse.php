<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 */

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class UserResponse extends Response {

	public $exists = false;
	public $agencyId;
	public $userId;
	public $firstName;
	public $lastName;
	public $lang;
	public $loanedItems;
	public $email;
	public $phone;
	public $postalAddress;

	protected $dom;
	protected $template = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
		<ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
		   <ns1:LookupUserResponse>
		      <ns1:UserId>
		         <ns1:AgencyId>{{agencyId}}</ns1:AgencyId>
		         <ns1:UserIdentifierValue>{{userId}}</ns1:UserIdentifierValue>
		      </ns1:UserId>
		      <ns1:UserOptionalFields>
		         <ns1:NameInformation>
		            <ns1:PersonalNameInformation>
		               <ns1:StructuredPersonalUserName>
		                  <ns1:GivenName>{{firstName}}</ns1:GivenName>
		                  <ns1:Surname>{{lastName}}</ns1:Surname>
		               </ns1:StructuredPersonalUserName>
		            </ns1:PersonalNameInformation>
		         </ns1:NameInformation>
		         <ns1:UserAddressInformation>
		            <ns1:UserAddressRoleType>mailto</ns1:UserAddressRoleType>
		            <ns1:ElectronicAddress>
		               <ns1:ElectronicAddressType>mailto</ns1:ElectronicAddressType>
		               <ns1:ElectronicAddressData>{{email}}</ns1:ElectronicAddressData>
		            </ns1:ElectronicAddress>
		         </ns1:UserAddressInformation>
		         <ns1:UserAddressInformation>
		            <ns1:UserAddressRoleType>sms</ns1:UserAddressRoleType>
		            <ns1:ElectronicAddress>
		               <ns1:ElectronicAddressType>sms</ns1:ElectronicAddressType>
		               <ns1:ElectronicAddressData>{{phone}}</ns1:ElectronicAddressData>
		            </ns1:ElectronicAddress>
		         </ns1:UserAddressInformation>
		         <ns1:UserAddressInformation>
		            <ns1:UserAddressRoleType>Permanent</ns1:UserAddressRoleType>
		            <ns1:PhysicalAddress>
		               <ns1:UnstructuredAddress>
		                  <ns1:UnstructuredAddressType>Newline-Delimited Text</ns1:UnstructuredAddressType>
		                  <ns1:UnstructuredAddressData>{{postalAddress}}</ns1:UnstructuredAddressData>
		               </ns1:UnstructuredAddress>
		               <ns1:PhysicalAddressType>Postal Address</ns1:PhysicalAddressType>
		            </ns1:PhysicalAddress>
		         </ns1:UserAddressInformation>
		         <ns1:UserLanguage>eng</ns1:UserLanguage>
		      </ns1:UserOptionalFields>{{loanedItems}}
		   </ns1:LookupUserResponse>
		</ns1:NCIPMessage>';

	protected $loanTemplate = '
		      <ns1:LoanedItem>
		         <ns1:ItemId>
		            <ns1:ItemIdentifierValue>{{id}}</ns1:ItemIdentifierValue>
		         </ns1:ItemId>
		         <ns1:ReminderLevel>{{reminderLevel}}</ns1:ReminderLevel>
		         <ns1:DateDue>{{dateDue}}</ns1:DateDue>
		         <ns1:Amount>
		            <ns1:CurrencyCode>NOK</ns1:CurrencyCode>
		            <ns1:MonetaryValue>0</ns1:MonetaryValue>
		         </ns1:Amount>
		         <ns1:Title>{{title}}</ns1:Title>
		      </ns1:LoanedItem>';
		      /* Not implemented yet:
		      <ns1:Ext>
		            <ns1:BibliographicDescription>
		               <ns1:Author>Hirvensalo, Mika</ns1:Author>
		               <ns1:BibliographicRecordId>
		                  <ns1:BibliographicRecordIdentifier>000719641</ns1:BibliographicRecordIdentifier>
		                  <ns1:BibliographicRecordIdentifierCode>Accession Number</ns1:BibliographicRecordIdentifierCode>
		               </ns1:BibliographicRecordId>
		               <ns1:Edition/>
		               <ns1:Pagination>XI, 190 s.</ns1:Pagination>
		               <ns1:PublicationDate>2001</ns1:PublicationDate>
		               <ns1:Publisher>Springer</ns1:Publisher>
		               <ns1:Title>Quantum computing</ns1:Title>
		               <ns1:Language>eng</ns1:Language>
		               <ns1:MediumType>Book</ns1:MediumType>
		            </ns1:BibliographicDescription>
		         </ns1:Ext>
		         */

	/**
	 * Create a new Ncip user response
	 *
	 * @param  QuiteSimpleXMLElement  $dom
	 * @return void
	 */
	public function __construct(QuiteSimpleXMLElement $dom = null)
	{
		$this->loanedItems = array();
		if (is_null($dom)) return;

		$this->dom = $dom->first('/ns1:NCIPMessage/ns1:LookupUserResponse');

		if ($this->dom->first('ns1:Problem')) {
			$this->exists = false;
		} else {
			$this->exists = true;
			$uinfo = $this->dom->first('ns1:UserOptionalFields');
			$this->userId = $this->dom->text('ns1:UserId/ns1:UserIdentifierValue');
			$this->agencyId = $this->dom->text('ns1:UserId/ns1:AgencyId');
			$this->firstName = $uinfo->text('ns1:NameInformation/ns1:PersonalNameInformation/ns1:StructuredPersonalUserName/ns1:GivenName');
			$this->lastName = $uinfo->text('ns1:NameInformation/ns1:PersonalNameInformation/ns1:StructuredPersonalUserName/ns1:Surname');
			$this->postalAddress = $uinfo->text('ns1:UserAddressInformation/ns1:PhysicalAddress/ns1:UnstructuredAddress/ns1:UnstructuredAddressData');
			$this->lang = $uinfo->text('ns1:UserLanguage');
			$this->loanedItems = array();

			foreach ($uinfo->xpath('ns1:UserAddressInformation') as $adrinfo) {
				if ($adrinfo->text('ns1:UserAddressRoleType') == 'mailto') {
					$this->email = $adrinfo->text('ns1:ElectronicAddress/ns1:ElectronicAddressData');
				} else if ($adrinfo->text('ns1:UserAddressRoleType') == 'sms') {
					$this->phone = $adrinfo->text('ns1:ElectronicAddress/ns1:ElectronicAddressData');
				}
			}

			foreach ($this->dom->xpath('ns1:LoanedItem') as $loanedItem) {
				$this->loanedItems[] = array(
					'id' => $loanedItem->text('ns1:ItemId/ns1:ItemIdentifierValue'),
					'reminderLevel' => $loanedItem->text('ns1:ReminderLevel'),
					'dateDue' => $this->parseDateTime($loanedItem->text('ns1:DateDue')),
					'title' => $loanedItem->text('ns1:Title')
				);
				// TODO: Add ns1:Ext/ns1:BibliographicDescription ?
			}

		}

	}

	/**
	 * Return a XML representation of the request
	 */
	public function xml()
	{
		$userFields = array('agencyId', 'userId', 'firstName', 'lastName', 'email', 'phone', 'postalAddress');
		$loanFields = array('id', 'reminderLevel', 'title');

		$loanedItems = '';
		foreach ($this->loanedItems as $item) {
			$s = $this->loanTemplate;
			foreach ($loanFields as $field) {
				$s = str_replace('{{' . $field . '}}', $item[$field], $s);
			}
			$s = str_replace('{{dateDue}}', $this->formatDateTime($item['dateDue']), $s);
			$loanedItems .= $s;
		}

		$s = $this->template;
		foreach ($userFields as $field) {
			$s = str_replace('{{' . $field . '}}', $this->{$field}, $s);
		}
		$s = str_replace('{{loanedItems}}', $loanedItems, $s);

		return $s;
	}

}