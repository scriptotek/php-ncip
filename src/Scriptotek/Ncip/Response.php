<?php namespace Scriptotek\Ncip;

use Danmichaelo\QuiteSimpleXMLElement\QuiteSimpleXMLElement;
use Carbon\Carbon;

class Response {

    /** @var QuiteSimpleXMLElement */
	protected $dom;

    /** @var bool */
	public $success;

    /** @var string */
	public $error;

    /** @var string */
	public $errorDetails;

    /** @var array */
	protected $args;

    /** @var array */
	protected $successArgs;

    /** @var array */
	protected $failureArgs;

	/**
	 * Create a new Response
	 *
	 * @param  QuiteSimpleXMLElement  $dom
	 * @return void
	 */
	public function __construct(QuiteSimpleXMLElement $dom = null)
	{
		$this->dom = $dom;
		$this->success = false;

		if (is_null($this->dom)) {
			return;
		}

		if ($this->dom->first('ns1:Problem')) {
			$this->error = $this->dom->text('ns1:Problem/ns1:ProblemType');
			$this->errorDetails = $this->dom->text('ns1:Problem/ns1:ProblemDetail');
		} else {
			$this->success = true;			
		}
		
	}

	public function parseDateTime($datestring) {
		return new Carbon($datestring);
	}

	public function formatDateTime($datetime) {
		return $datetime->format(\DateTime::ISO8601); // does not include milliseconds
	}

	protected function parseBibliographicDescription($b)
	{
		$a = array(
			'title' => $b->text('ns1:Title'),
			'author' => $b->text('ns1:Author'),
			'publicationDate' => $b->text('ns1:PublicationDate'),
			'publisher' => $b->text('ns1:Publisher'),
			'objektid' => $b->text('ns1:BibliographicRecordId/ns1:BibliographicRecordIdentifier'),
			'edition' => $b->text('ns1:Edition'),
			'pagination' => $b->text('ns1:Pagination'),
			'language' => $b->text('ns1:Language'),
			'mediumType' => $b->text('ns1:MediumType')
		);
		return $a;
	}

	/**
	 * Validate a set of arguments
	 *
	 * @return void
	 */
	public function validateArgSet($set)
	{
		if (isset($set)) {
			foreach ($set as $arg) {
				if (!isset($this->{$arg})) {
					throw new \Exception('Response not valid: ' . $arg . ' has not been set!');
				}
			}
		}
		
	}

	/**
	 * Validate the response
	 *
	 * @return void
	 */
	protected function validate()
	{
		$this->validateArgSet($this->args);

		if ($this->success) {
			$this->validateArgSet($this->successArgs);
		}

		if (!$this->success) {
			$this->validateArgSet($this->failureArgs);
		}
	}

}
