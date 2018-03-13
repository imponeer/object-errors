<?php

namespace ImpressPHP\ObjectErrors;

use ArrayAccess;
use Countable;
use JsonSerializable;
use Serializable;

/**
 * Collection of errors
 *
 * @package ImpressPHP\ObjectErrors
 */
class ErrorsCollection implements ArrayAccess, Countable, Serializable, JsonSerializable
{
	/**
	 * Mode that says that only one param for adding is used
	 */
	const MODE_1_PARAM = 0;

	/**
	 * Mode that says two params are used
	 */
	const MODE_2_PARAMS = 1;

	/**
	 * Mode that says that 2nd param is a used as prefix
	 */
	const MODE_2_AS_PREFIX = 2;
	/**
	 * Mode how this errors collection works
	 *
	 * @var int
	 */
	public $mode = self::MODE_1_PARAM;
	/**
	 * Errors data
	 *
	 * @var array
	 */
	private $errors = [];

	/**
	 * ErrorsCollection constructor.
	 *
	 * @param int $mode
	 */
	public function __construct($mode = self::MODE_1_PARAM)
	{
		$this->mode = $mode;
	}

	/**
	 * Checks if offset exists
	 *
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->errors[$offset]);
	}

	/**
	 * Gets by pos
	 *
	 * @param mixed $offset
	 *
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->errors[$offset];
	}

	/**
	 * Sets by pos
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->errors[$offset] = $value;
	}

	/**
	 * Tries to unset by offset but instead returns error
	 *
	 * @param mixed $offset
	 *
	 * @throws UnsetErrorException
	 */
	public function offsetUnset($offset)
	{
		throw new UnsetErrorException();
	}

	/**
	 * Is empty?
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->errors);
	}

	/**
	 * Clear errors list
	 */
	public function clear()
	{
		$this->errors = [];
	}

	/**
	 * Gets errors list as HTML
	 *
	 * @return string html listing the errors
	 */
	public function getHtml()
	{
		return nl2br(
			$this->__toString()
		);
	}

	/**
	 * Converts errors list to string
	 *
	 * @return string
	 */
	public function __toString()
	{
		if (empty($this->errors)) {
			return '';
		} else {
			return implode(PHP_EOL, $this->errors);
		}
	}

	/**
	 * Adds an
	 *
	 * @param array ...$err_data
	 */
	public function add(...$err_data)
	{
		switch ($this->mode) {
			case self::MODE_1_PARAM:
				$this->errors[] = $err_data[0];
				break;
			case self::MODE_2_AS_PREFIX:
				if (is_array($err_data[0])) {
					if (!isset($err_data[1])) {
						$err_data[1] = false;
					}
					foreach ($err_data[0] as $str) {
						$this->add($str, $err_data[1]);
					}
					return;
				}
				if (isset($err_data[1]) && ($err_data[1] !== false)) {
					$err_data[0] = "[" . $err_data[1] . "] " . $err_data[0];
				}
				$this->errors[] = $err_data[0];
				break;
			case self::MODE_2_PARAMS:
				$this->errors[trim($err_data[0])] = trim($err_data[1]);
				break;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function count()
	{
		return count($this->errors);
	}

	/**
	 * @inheritDoc
	 */
	public function serialize()
	{
		return serialize([
			$this->mode,
			$this->errors
		]);
	}

	/**
	 * @inheritDoc
	 */
	public function unserialize($serialized)
	{
		list($this->mode, $this->errors) = unserialize($serialized);
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		return $this->errors;
	}

	/**
	 * Export data to array
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->errors;
	}

	/**
	 * Export data to json
	 *
	 * @return string
	 */
	public function toJson()
	{
		return json_encode($this);
	}

}