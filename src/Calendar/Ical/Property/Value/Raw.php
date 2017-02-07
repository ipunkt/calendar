<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

/**
 * Raw value.
 */
class Raw implements Value
{
    /**
     * String.
     *
     * @var string
     */
    protected $string;

    /**
     * Create a new raw value.
     *
     * @param  string $string
     */
    public function __construct($string)
    {
        $this->setRaw($string);
    }

    /**
     * fromString(): defined by Value interface.
     *
     * @see    Value::fromString()
     * @param  string $string
     * @return Value
     */
    public static function fromString($string)
    {
        return new self($string);
    }

    /**
     * Set raw.
     *
     * @param  string $string
     * @return self
     */
    public function setRaw($string)
    {
        $this->string = (string)$string;

        return $this;
    }

    /**
     * Get raw.
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->string;
    }
}