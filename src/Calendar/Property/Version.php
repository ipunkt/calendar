<?php

namespace Ipunkt\Calendar\Property;

class Version extends TextProperty
{
    protected $name = 'VERSION';

    public function __construct($value = '2.0', $defaultValue = '2.0')
    {
        parent::__construct($value, $defaultValue);
    }

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