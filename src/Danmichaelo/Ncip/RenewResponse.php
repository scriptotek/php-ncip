<?php namespace Danmichaelo\Ncip;
/*
 * (c) Dan Michael O. Heggø (2013)
 *
 * Basic Ncip library. This class currently only implements
 * a small subset of the NCIP services.
 *
 * Example response (failed):
 *
 *	 <ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 *	   <ns1:RenewItemResponse>
 *	      <ns1:Problem>
 *	         <ns1:ProblemType>Maximum renewals exceeded.</ns1:ProblemType>
 *	         <ns1:ProblemDetail>Maximum renewals exceeded.</ns1:ProblemDetail>
 *	      </ns1:Problem>
 *	      <ns1:Ext>
 *	         <ns1:UserOptionalFields>
 *	            <ns1:UserLanguage>eng</ns1:UserLanguage>
 *	         </ns1:UserOptionalFields>
 *	      </ns1:Ext>
 *	   </ns1:RenewItemResponse>
 *	</ns1:NCIPMessage>
 *
 * Example response (successful):
 *
 *	  <ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 *	     <ns1:RenewItemResponse>
 *	        <ns1:ItemId>
 *	           <ns1:ItemIdentifierValue>13k112494</ns1:ItemIdentifierValue>
 *	        </ns1:ItemId>
 *	        <ns1:DateDue>2013-11-11T00:30:35.247+01:00</ns1:DateDue>
 *	        <ns1:Ext>
 *	           <ns1:UserOptionalFields>
 *	              <ns1:UserLanguage>eng</ns1:UserLanguage>
 *	           </ns1:UserOptionalFields>
 *	        </ns1:Ext>
 *	     </ns1:RenewItemResponse>
 *	  </ns1:NCIPMessage>
 */
class RenewResponse extends Response {

	public $success;
	public $error;
	public $dueDate;

	/**
	 * Create a new Ncip user response
	 *
	 * @param  QuiteSimpleXMLElement  $dom
	 * @return void
	 */
	public function __construct($dom = null)
	{

		if (is_null($dom)) {

			$this->success = false;
			$this->error = 'Empty response';

		} else {
			$this->dom = $dom->first('/ns1:NCIPMessage/ns1:RenewItemResponse');

			if ($this->dom->first('ns1:Problem')) {
				$this->success = false;
				$this->error = $this->dom->text('ns1:Problem/ns1:ProblemDetail');
			} else {
				$this->success = true;
				$this->dueDate = $this->parseDateTime($this->dom->text('ns1:DateDue'));
			}
		}

	}

}
