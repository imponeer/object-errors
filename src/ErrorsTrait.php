<?php

namespace Imponeer\ObjectErrors;

/**
 * Trait that can be used when replacing old ImpressCMS or it's modules code
 * Or maybe somewhere else... if you want it!
 */
trait ErrorsTrait
{
    /**
     * Errors collection
     */
    protected ErrorsCollection $errors;

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
     * @param    bool $asHTML Format using HTML?
     *
     * @return array|string an array of errors
     */
    public function getErrors(bool $asHTML = true): array|string
    {
        return $asHTML ? $this->getHtmlErrors() : $this->errors->toArray();
    }

    /**
     * add an error
     *
     * @param string $err_str error to add
     */
    public function setErrors(string $err_str): void
    {
        call_user_func_array([$this->errors, 'add'], func_get_args());
    }

    /**
     * Returns the errors for this object as html
     */
    public function getHtmlErrors(): string
    {
        return $this->errors->getHtml();
    }

    /**
     * Has some errors?
     */
    public function hasError(): bool
    {
        return !$this->errors->isEmpty();
    }
}
