<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types;

use Ipunkt\Calendar\Calendar;

/**
 * Class Binary
 *
 * Purpose: This value type is used to identify properties that contain
 * a character encoding of inline binary data.  For example, an inline
 * attachment of a document might be included in an iCalendar object.
 *
 * Format Definition:  This value type is defined by the following notation:
 *
 * binary     = *(4b-char) [b-end]
 * ; A "BASE64" encoded character string, as defined by [RFC4648].
 *
 * b-end      = (2b-char "==") / (3b-char "=")
 *
 * b-char = ALPHA / DIGIT / "+" / "/"
 *
 * Description:  Property values with this value type MUST also include
 * the inline encoding parameter sequence of ";ENCODING=BASE64".
 * That is, all inline binary data MUST first be character encoded using
 * the "BASE64" encoding method defined in [RFC2045]. No additional content
 * value encoding (i.e., BACKSLASH character encoding, see Section 3.3.11)
 * is defined for this value type.
 *
 * Example:  The following is an example of a "BASE64" encoded binary value data:
 *
 * ATTACH;FMTTYPE=image/vnd.microsoft.icon;ENCODING=BASE64;VALUE
 * =BINARY:AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAA
 * AAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAAAAAgIAAAICAgADAwMAA////AAAA
 * AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
 * AAAAAAAAAAAAAAAAAAAAAAMwAAAAAAABNEMQAAAAAAAkQgAAAAAAJEREQgAA
 * ACECQ0QgEgAAQxQzM0E0AABERCRCREQAADRDJEJEQwAAAhA0QwEQAAAAAERE
 * AAAAAAAAREQAAAAAAAAkQgAAAAAAAAMgAAAAAAAAAAAAAAAAAAAAAAAAAAAA
 * AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
 * AAAAAAAAAAAA
 */
class Binary extends BaseType
{
    /**
     * creates a type value from string
     *
     * @param string $string
     * @return Binary
     * @throws \Exception when no valid string given
     */
    public static function createFromString($string)
    {
        $pattern = '~.*?ENCODING=BASE64' . Calendar::MULTIPLE_PART_SEPARATOR . 'VALUE=BINARY\:(.*)~ms';
        if (!preg_match($pattern, $string, $matches)) {
            throw new \Exception('No valid string for Binary type given');
        }

        $string = rtrim($matches[1], Calendar::END_OF_LINE);
        $type = new self($string);
        $type->setValue($string, true);

        return $type;
    }

    /**
     * @param string $value
     * @param bool $isEncoded
     * @return $this
     */
    public function setValue($value, $isEncoded = false)
    {
        if ($isEncoded) {
            $value = base64_decode($value);
        }

        return parent::setValue($value);
    }

    /**
     * returns a string representation of the type value
     *
     * @return string
     */
    public function toString()
    {
        if ($this->value === $this->defaultValue) {
            return '';
        }

        return 'ENCODING=BASE64' . Calendar::MULTIPLE_PART_SEPARATOR . 'VALUE=BINARY:' . $this->getStringValue();
    }

    /**
     * returns encoded value
     *
     * @return string
     */
    public function getStringValue()
    {
        return base64_encode($this->value);
    }
}