<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

/**
 * Value interface.
 */
interface Value
{
    /**
     * Create a new value from a string.
     *
     * @param  string $string
     * @return Value
     */
    public static function fromString($string);
}
