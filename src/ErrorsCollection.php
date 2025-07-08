<?php

namespace Imponeer\ObjectErrors;

use ArrayAccess;
use Countable;
use JsonException;
use JsonSerializable;
use Stringable;

/**
 * Collection of errors
 */
class ErrorsCollection implements ArrayAccess, Countable, JsonSerializable, Stringable
{
    /**
     * Errors data
     */
    private array $errors = [];

    /**
     * ErrorsCollection constructor.
     *
     * @param ParamsMode $mode Mode how this errors collection works
     */
    public function __construct(
		public readonly ParamsMode $mode = ParamsMode::Mode1
	)
    {
    }

    /**
     * Checks if offset exists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
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
    public function offsetGet(mixed $offset): mixed
    {
        return $this->errors[$offset];
    }

    /**
     * Sets by pos
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
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
    public function offsetUnset(mixed $offset): never
    {
        throw new UnsetErrorException();
    }

    /**
     * Is empty?
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->errors);
    }

    /**
     * Clear errors list
     */
    public function clear(): void
    {
        $this->errors = [];
    }

    /**
     * Gets errors list as HTML
     *
     * @return string html listing the errors
     */
    public function getHtml(): string
    {
        return nl2br(
            (string)$this
        );
    }

    /**
     * Converts errors list to string
     *
     * @return string
     */
    public function __toString(): string
    {
        if (empty($this->errors)) {
            return '';
        }

		return implode(PHP_EOL, $this->errors);
	}

    /**
     * Adds an
     */
    public function add(mixed ...$err_data): void
    {
        switch ($this->mode) {
            case ParamsMode::Mode1:
                $this->errors[] = (string) $err_data[0];
                break;
            case ParamsMode::Mode2AsPrefix:
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
                    $err_data[0] = sprintf("[%s] %s", $err_data[1], $err_data[0]);
                }
                $this->errors[] = $err_data[0];
                break;
            case ParamsMode::Mode2:
                $this->errors[trim((string) $err_data[0])] = trim((string) $err_data[1]);
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->errors);
    }

    public function __serialize(): array
    {
        return [
            $this->mode,
            $this->errors
        ];
    }

    public function __unserialize(array $data): void
    {
        [$this->mode, $this->errors] = $data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->errors;
    }

    /**
     * Export data to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->errors;
    }

	/**
	 * Export data to json
	 *
	 * @throws JsonException
	 */
    public function toJson(): string
    {
        return json_encode($this, JSON_THROW_ON_ERROR);
    }

}