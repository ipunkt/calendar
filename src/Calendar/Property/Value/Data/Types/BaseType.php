<?php
/**
 * calendar
 *
 * @author rok
 * @since 08.12.13
 */

namespace Ipunkt\Calendar\Property\Value\Data\Types;


use Ipunkt\Calendar\Contracts\Property\Value\Data\Types\Type as TypeInterface;

class BaseType implements TypeInterface {

	/**
	 * Type value
	 *
	 * @var mixed|null
	 */
	protected $value;

	/**
	 * Default type value, value will not be rendered
	 *
	 * @var mixed|null
	 */
	protected $defaultValue = null;

	/**
	 * creates a type value instance
	 *
	 * @param null|mixed $value
	 * @param null|mixed $defaultValue
	 */
	public function __construct($value = null, $defaultValue = null)
	{
		$this->setValue($value);
		$this->defaultValue = $defaultValue;
	}

	/**
	 * sets the value
	 *
	 * @param mixed $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * returns the value
	 *
	 * @return null|mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * returns the string representation
	 *
	 * @return string
	 */
	public function getStringValue()
	{
		if ($this->defaultValue === $this->getValue())
		{
			return '';
		}

		return $this->value;
	}

	/**
	 * returns a string representation of the Type
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->getStringValue();
	}

	/**
	 * magic method: returns string representation
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * creates a type value from string
	 *
	 * @param string $string
	 * @return BaseType
	 */
	public static function createFromString($string)
	{
		return new self($string);
	}
}