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

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class RenewResponse extends Response {

	public $success;
	public $id;
	public $dueDate;
	public $error;
	public $errorDetails;

	protected $template = '
 		  <ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 		     <ns1:RenewItemResponse>{{main}}
 		      <ns1:Ext>
 		         <ns1:UserOptionalFields>
 		            <ns1:UserLanguage>{{language}}</ns1:UserLanguage>
 		         </ns1:UserOptionalFields>
 		      </ns1:Ext>
 		     </ns1:RenewItemResponse>
 		  </ns1:NCIPMessage>';

	protected $template_success = '
 		        <ns1:ItemId>
 		           <ns1:ItemIdentifierValue>{{id}}</ns1:ItemIdentifierValue>
 		        </ns1:ItemId>
 		        <ns1:DateDue>{{dateDue}}</ns1:DateDue>';

	protected $template_failure = '
 		      <ns1:Problem>
 		         <ns1:ProblemType>{{error}}</ns1:ProblemType>
 		         <ns1:ProblemDetail>{{errorDetails}}</ns1:ProblemDetail>
 		      </ns1:Problem>';
	/**
	 * Create a new Ncip user response
	 *
	 * @param  QuiteSimpleXMLElement  $dom
	 * @return void
	 */
	public function __construct(QuiteSimpleXMLElement $dom = null)
	{

		if (is_null($dom)) {

			$this->success = false;
			$this->error = 'Empty response';

		} else {
			$this->dom = $dom->first('/ns1:NCIPMessage/ns1:RenewItemResponse');

			if ($this->dom->first('ns1:Problem')) {
				$this->success = false;
				$this->error = $this->dom->text('ns1:Problem/ns1:ProblemType');
				$this->errorDetails = $this->dom->text('ns1:Problem/ns1:ProblemDetail');
			} else {
				$this->success = true;
				$this->id = $this->dom->text('ns1:ItemId/ns1:ItemIdentifierValue');
				$this->dueDate = $this->parseDateTime($this->dom->text('ns1:DateDue'));
			}
		}

	}

	/**
	 * Return a XML representation of the request
	 */
	public function xml()
	{
		$s = $this->template;
		$s = str_replace('{{language}}', 'eng', $s);
		$s = str_replace('{{main}}', $this->success ? $this->template_success : $this->template_failure, $s);
		if ($this->success) {
			$s = str_replace('{{id}}', $this->id, $s);
			$s = str_replace('{{dateDue}}', $this->formatDateTime($this->dateDue), $s);
		} else {
			$s = str_replace('{{error}}', $this->error, $s);
			$s = str_replace('{{errorDetails}}', $this->errorDetails, $s);
		}
		return $s;
	}
}
