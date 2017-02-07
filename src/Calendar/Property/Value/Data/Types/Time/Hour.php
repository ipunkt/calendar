<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types\Time;

use Ipunkt\Calendar\Property\Value\Data\Types\Integer;

class Hour extends Integer
{
    /**
     * returns a string representation of type value
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