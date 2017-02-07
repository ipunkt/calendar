<?php

namespace Ipunkt\Calendar\Property;

class BaseProperty
{
    /**
     * Property name
     *
     * @var string
     */
    protected $name;

    /**
     * $value
     *
     * @var mixed
     */
    protected $value;

    /**
     * default value
     *
     * @var mixed|null
     */
    protected $defaultValue;

    /**
     * Creates a Property instance
     *
     * @param null|mixed $value
     * @param null|mixed $defaultValue
     */
    public function __construct($value = null, $defaultValue = null)
    {
        if (null !== $value) {
            $this->setValue($value);
        }

        if (null !== $defaultValue) {
            $this->defaultValue = $defaultValue;
        }
    }

    /**
     * sets value
     *
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * returns a string representation of the property
     *
     * @return string
     */
    public function toString()
    {
        if ($this->defaultValue === $this->getValue()) {
            return '';
        }

        return $this->name . ':' . $this->value;
    }

    /**
     * Magic methods: returns a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}