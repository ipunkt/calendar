<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

class Uri implements Value
{
    /**
     * uri
     *
     * @var string
     */
    protected $uri;

    /**
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Create a new value from a string.
     *
     * @param  string $string
     * @return Value
     */
    public static function fromString($string)
    {
        return new self($string);
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * set uri
     *
     * @param string $uri
     *
     * @return Uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }
}