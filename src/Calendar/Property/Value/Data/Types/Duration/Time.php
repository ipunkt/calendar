<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types\Duration;

use Ipunkt\Calendar\Property\Value\Data\Types\BaseType;

class Time extends BaseType
{
    /**
     * Creates a Time instance
     *
     * @param null|Hour|Minute|Second $value
     * @param null $defaultValue
     */
    public function __construct($value = null, $defaultValue = null)
    {
        $this->value = array(
            'dur-hour' => new Hour(),
            'dur-minute' => new Minute(),
            'dur-second' => new Second(),
        );

        if (null !== $value) {
            $this->setValue($value);
        }

        $this->defaultValue = $defaultValue;
    }

    /**
     * sets a part value
     *
     * @param Hour|Minute|Second $value
     * @return $this
     */
    public function setValue($value)
    {
        $key = null;
        switch (get_class($value)) {
            case Hour::class:
                $key = 'dur-hour';
                break;

            case Minute::class:
                $key = 'dur-minute';
                break;

            case Second::class:
                $key = 'dur-second';
                break;
        }

        if (isset($this->value[$key])) {
            $this->value[$key] = $value;
        }

        return $this;
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

        $time = implode('', $this->value);
        if ($time === '') {
            return '';
        }

        return 'T' . $time;
    }
}