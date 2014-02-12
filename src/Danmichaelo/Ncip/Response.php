<?php namespace Danmichaelo\Ncip;

use Carbon\Carbon;

class Response {

	protected $dom;

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

	protected function validate()
	{
		if (isset($this->args)) {			
			foreach ($this->args as $arg) {
				if (!isset($this->{$arg})) {
					throw new \Exception('Response not valid: ' . $arg . ' has not been set!');
				}
			}
		}
		if (isset($this->successArgs) && $this->success) {			
			foreach ($this->successArgs as $arg) {
				if (!isset($this->{$arg})) {
					throw new \Exception('Response not valid: ' . $arg . ' has not been set!');
				}
			}
		}
		if (isset($this->failureArgs) && !$this->success) {			
			foreach ($this->failureArgs as $arg) {
				if (!isset($this->{$arg})) {
					throw new \Exception('Response not valid: ' . $arg . ' has not been set!');
				}
			}
		}
	}

}
