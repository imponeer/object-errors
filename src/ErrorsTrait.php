<?php

namespace ImpressPHP\ObjectErrors;

trait ErrorsTrait
{

	/**
	 * Errors collection
	 *
	 * @var ErrorsCollection
	 */
	protected $errors;

	/**
	 * ErrorsTrait constructor.
	 */
	public function __construct()
	{
		$this->errors = new ErrorsCollection();
	}

	/**
	 * return the errors for this object as an array
	 *
	 * @param    bool $ashtml Format using HTML?
	 *
	 * @return array|string an array of errors
	 */
	public function getErrors($ashtml = true)
	{
		return $ashtml ? $this->getHtmlErrors() : $this->errors->toArray();
	}

	/**
	 * add an error
	 *
	 * @param string $err_str error to add
	 */
	public function setErrors($err_str)
	{
		call_user_func_array([$this->errors, 'add'], func_get_args());
	}

	/**
	 * Returns the errors for this object as html
	 *
	 * @return string
	 */
	public function getHtmlErrors()
	{
		return $this->errors->getHtml();
	}

	/**
	 * Has some errors
	 *
	 * @return bool
	 */
	public function hasError()
	{
		return !$this->errors->isEmpty();
	}

}