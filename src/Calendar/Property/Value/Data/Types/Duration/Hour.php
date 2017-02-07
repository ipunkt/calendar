<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types\Duration;

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
        if ($this->defaultValue === $this->getValue() || $this->value === '') {
            return '';
        }

        return $this->value . 'H';
    }
}