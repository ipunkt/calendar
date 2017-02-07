<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

/**
 * Text value.
 */
class Text implements Value
{
    /**
     * Text.
     *
     * @var string
     */
    protected $text;

    /**
     * Create a new text value.
     *
     * @param  string $text
     */
    public function __construct($text)
    {
        $this->setText($text);
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
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param  string $text
     * @return self
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
}