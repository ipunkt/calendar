<?php

namespace Ipunkt\Calendar\Property;

class ProductIdentifier extends TextProperty
{
    protected $name = 'PRODID';

    /**
     * returns a string representation of the property
     *
     * @return string
     */
    public function toString()
    {
        return $this->name . ':' . $this->value;
    }
}