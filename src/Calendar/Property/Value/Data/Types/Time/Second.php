<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types\Time;

use Ipunkt\Calendar\Property\Value\Data\Types\Integer;

class Second extends Integer
{
    /**
     * Creates a Second instance
     *
     * @param int $value
     * @param int $defaultValue
     */
    public function __construct($value = 0, $defaultValue = 0)
    {
        parent::__construct($value, $defaultValue);
    }

    /**
     * returns string representation of type value
     *
     * @return string
     */
    public function getStringValue()
    {
        if ($this->defaultValue === $this->getValue()) {
            return '';
        }

        return str_pad($this->value, 2, '0', STR_PAD_LEFT);
    }
}