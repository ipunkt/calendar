<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types\Duration;

use Ipunkt\Calendar\Property\Value\Data\Types\BaseType;

class Date extends BaseType
{

    /**
     * Creates a Date instance
     *
     * @param null|Day|Time $value
     * @param null $defaultValue
     */
    public function __construct($value = null, $defaultValue = null)
    {
        $this->value = array(
            'dur-day' => new Day(),
            'dur-time' => new Time(),
        );

        if (null !== $value) {
            $this->setValue($value);
        }

        $this->defaultValue = $defaultValue;
    }

    /**
     * Sets a component value
     *
     * @param Day|Time $value
     * @return $this
     */
    public function setValue($value)
    {
        $key = null;
        switch (get_class($value)) {
            case Day::class:
                $key = 'dur-day';
                break;

            case Time::class:
                $key = 'dur-time';
                break;
        }

        if (isset($this->value[$key])) {
            $this->value[$key] = $value;
        }

        return $this;
    }

    /**
     * returns a string representation of value
     *
     * @return string
     */
    public function getStringValue()
    {
        if ($this->defaultValue === $this->getValue()) {
            return '';
        }

        if ($this->value['dur-day']->toString() === '') {
            return '';
        }

        return implode('', $this->value);
    }
}