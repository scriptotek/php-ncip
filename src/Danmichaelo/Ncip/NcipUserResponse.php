<?php namespace Danmichaelo\Ncip;
/* 
 * (c) Dan Michael O. Heggø (2013)
 * 
 * Basic Ncip library. This class currently only implements 
 * a small subset of the NCIP services.
 */

class NcipUserResponse extends NcipResponse {

	public $exists;
	public $agencyId;
	public $userId;
	public $firstName;
	public $lastName;
	public $lang;
	public $loanedItems;
	public $email;
	public $phone;

	/**
	 * Create a new Ncip user response
	 *
	 * @param  CustomXMLElement  $dom
	 * @return void
	 */
	public function __construct($dom)
	{
		$this->dom = $dom;

		if ($this->dom->first('ns1:Problem')) {
			$this->exists = false;
		} else {
			$this->exists = true;
			$uinfo = $this->dom->first('ns1:UserOptionalFields');
			$this->userId = $this->dom->text('ns1:UserId/ns1:UserIdentifierValue');
			$this->agencyId = $this->dom->text('ns1:UserId/ns1:AgencyId');
			$this->firstName = $uinfo->text('ns1:NameInformation/ns1:PersonalNameInformation/ns1:StructuredPersonalUserName/ns1:GivenName');
			$this->lastName = $uinfo->text('ns1:NameInformation/ns1:PersonalNameInformation/ns1:StructuredPersonalUserName/ns1:Surname');
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

}