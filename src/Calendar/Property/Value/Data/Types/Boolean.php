<?php
/**
 * calendar
 *
 * @author rok
 * @since 08.12.13
 */

namespace Ipunkt\Calendar\Property\Value\Data\Types;

/**
 * Class Boolean
 *
 * Purpose:  This value type is used to identify properties that contain
 * either a "TRUE" or "FALSE" Boolean value.
 *
 * Format Definition:  This value type is defined by the following
 * notation:
 *
 * boolean    = "TRUE" / "FALSE"
 *
 * Description:  These values are case-insensitive text.  No additional
 * content value encoding (i.e., BACKSLASH character encoding, see
 * Section 3.3.11) is defined for this value type.
 *
 * Example:  The following is an example of a hypothetical property that
 * has a BOOLEAN value type:
 *
 * TRUE
 */
class Boolean extends BaseType
{

	/**
	 * creates a type value from string
	 *
	 * @param string $string
	 * @return BaseType
	 */
	public static function createFromString($string)
	{
		$string = strtoupper($string);
		$value = ($string === 'TRUE');

		return new self($value);
	}

	/**
	 * sets the value
	 *
	 * @param bool $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = ($value === true);

		return $this;
	}

	/**
	 * returns a string representation
	 *
	 * @return string
	 */
	public function getStringValue()
	{
		if ($this->defaultValue === $this->getValue()) {
			return '';
		}

		return $this->value ? 'TRUE' : 'FALSE';
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
}