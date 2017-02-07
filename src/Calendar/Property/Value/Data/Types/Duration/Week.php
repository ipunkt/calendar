<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types\Duration;

use Ipunkt\Calendar\Property\Value\Data\Types\Integer;

class Week extends Integer
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

        return $this->value . 'W';
    }
}