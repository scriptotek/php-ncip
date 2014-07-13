<?php namespace Scriptotek\Ncip;


interface RequestInterface {

	/**
	 * Return XML representation of the request
	 *
	 * @return string
	 */
	public function xml();

	/**
	 * Check if Request is of a specific class
	 *
	 * @param string $kind
	 * @return bool
	 */
	public function is($kind);

}
