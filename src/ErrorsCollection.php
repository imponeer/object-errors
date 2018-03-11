<?php

namespace ImpressPHP\ObjectErrors;

use ArrayAccess;
use Iterator;

class ErrorsCollection implements ArrayAccess, Iterator
{

    /**
     * Errors data
     *
     * @var array
     */
    private $errors = [];

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
     * Gets current error msg
     *
     * @return string
     */
    public function current()
    {
        return current($this->errors);
    }

    /**
     * Gets cuurrent offset
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->errors);
    }

    /**
     * Moves internal pointer by one position
     */
    public function next()
    {
        next($this->errors);
    }

    /**
     * Moves internal cursor at start
     */
    public function rewind()
    {
        rewind($this->errors);
    }

    /**
     * Is position valid?
     *
     * @return bool
     */
    public function valid()
    {
        return true;
    }

    /**
     * Do we have some errors?
     *
     * @return bool
     */
    public function has()
    {
        return empty($this->errors) === false;
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
     * Add an error
     *
     * @param string $err_str error to add
     */
    public function add($err_str)
    {
        if (func_num_args() > 1) {
            list($id, $err_str) = func_get_args();
            $this->errors[$id] = trim($err_str);
        } else {
            $this->errors[] = trim($err_str);
        }
    }
}