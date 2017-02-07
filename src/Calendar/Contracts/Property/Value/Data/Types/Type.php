<?php

namespace Ipunkt\Calendar\Contracts\Property\Value\Data\Types;

interface Type
{
    /**
     * returns a type value from string
     *
     * @param string|mixed $string
     * @return Type
     */
    public static function createFromString($string);

    /**
     * returns a string representation of the Type
     */
    public function toString();
}