<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types\Duration;

use Ipunkt\Calendar\Property\Value\Data\Types\Integer;

class Minute extends Integer
{
    /**
     * returns a string from type value
     *
     * @return string
     */
    public function getStringValue()
    {
        if ($this->defaultValue === $this->getValue() || $this->value === '') {
            return '';
        }

        return $this->value . 'M';
    }
}