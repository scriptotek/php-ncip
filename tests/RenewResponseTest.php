<?php namespace Scriptotek\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;

class RenewResponseTest extends \PHPUnit_Framework_TestCase {

	protected $dummy_response_success = '
 		  <ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 		     <ns1:RenewItemResponse>
 		        <ns1:ItemId>
 		           <ns1:ItemIdentifierValue>13k112494</ns1:ItemIdentifierValue>
 		        </ns1:ItemId>
 		        <ns1:DateDue>2013-11-11T00:30:35.247+01:00</ns1:DateDue>
 		        <ns1:Ext>
 		           <ns1:UserOptionalFields>
 		              <ns1:UserLanguage>eng</ns1:UserLanguage>
 		           </ns1:UserOptionalFields>
 		        </ns1:Ext>
 		     </ns1:RenewItemResponse>
 		  </ns1:NCIPMessage>';

	protected $dummy_response_fail = '
 		 <ns1:NCIPMessage xmlns:ns1="http://www.niso.org/2008/ncip">
 		   <ns1:RenewItemResponse>
 		      <ns1:Problem>
 		         <ns1:ProblemType>Maximum renewals exceeded</ns1:ProblemType>
 		         <ns1:ProblemDetail>Maximum renewals exceeded.</ns1:ProblemDetail>
 		      </ns1:Problem>
 		      <ns1:Ext>
 		         <ns1:UserOptionalFields>
 		            <ns1:UserLanguage>eng</ns1:UserLanguage>
 		         </ns1:UserOptionalFields>
 		      </ns1:Ext>
 		   </ns1:RenewItemResponse>
 		</ns1:NCIPMessage>';

	protected $dummy_response_errorneous = '
		<ns1:NCIPMessage ';

	public function testParseDummySuccessResponse() {
		$id = '13k112494';
		$dummy_response = new QuiteSimpleXMLElement($this->dummy_response_success);
		$response = new RenewResponse($dummy_response);
		$date1 = new \DateTime('2013-11-11T00:30:35+01:00');

		$this->assertInstanceOf('Scriptotek\Ncip\RenewResponse', $response);
		$this->assertTrue($response->success);
		$this->assertEquals($id, $response->id);
		$this->assertEquals($date1->getTimestamp(), $response->dateDue->getTimestamp());
	}

	public function testParseDummyFailResponse() {
		$dummy_response = new QuiteSimpleXMLElement($this->dummy_response_fail);
		$response = new RenewResponse($dummy_response);

		$this->assertInstanceOf('Scriptotek\Ncip\RenewResponse', $response);
		$this->assertFalse($response->success);
		$this->assertEquals('Maximum renewals exceeded', $response->error);
		$this->assertEquals('Maximum renewals exceeded.', $response->errorDetails);
	}

	public function testParseNullResponse() {
		$response = new RenewResponse(null);

		$this->assertInstanceOf('Scriptotek\Ncip\RenewResponse', $response);
	}

	public function testXmlSuccess()
	{
		$response = new RenewResponse;
		$response->id = '13k115558';
		$response->dateDue = new \DateTime('2014-12-12T00:00:00+02:00');
		$response->success = true;
		$xml = $response->xml();

		$this->assertContains('<ns1:ItemIdentifierValue>13k115558</ns1:ItemIdentifierValue>', $xml);
		$this->assertContains('<ns1:DateDue>2014-12-12T00:00:00+0200</ns1:DateDue>', $xml);

		$response = new RenewResponse(new QuiteSimpleXMLElement($xml));
		$this->assertTrue($response->success);
	}

	public function testXmlFailure()
	{
		$response = new RenewResponse;
		$response->id = '13k115558';
		$response->success = false;
		$response->error = 'Some error';
		$xml = $response->xml();

		$this->assertNotContains('<ns1:ItemIdentifierValue>13k115558</ns1:ItemIdentifierValue>', $xml);
		$this->assertContains('<ns1:ProblemType>Some error</ns1:ProblemType>', $xml);

		$response = new RenewResponse(new QuiteSimpleXMLElement($xml));
		$this->assertFalse($response->success);
	}

}
