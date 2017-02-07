<?php

namespace Ipunkt\Calendar\Ical;

use Ipunkt\Calendar\Ical\Component;
use Ipunkt\Calendar\Ical\Parser\Parser;

class Ical
{
    /**
     * List of calendars.
     *
     * @var array
     */
    protected $calendars = [];

    /**
     * Create an Ical object from a string.
     *
     * @param  string $string
     * @return Ical
     */
    public static function fromString($string)
    {
        return self::fromUri('data:text/calendar,' . $string);
    }

    /**
     * Create an Ical object from an URI.
     *
     * @param  string $uri
     * @return Ical
     */
    public static function fromUri($uri)
    {
        $parser = new Parser(fopen($uri, 'r'));

        return $parser->parse();
    }

    /**
     * Check if a string is an IANA token.
     *
     * @param  string $string
     * @return boolean
     */
    public static function isIanaToken($string)
    {
        return (bool)preg_match('(^[A-Za-z\d\-]+$)S', $string);
    }

    /**
     * Check if a string is an X-Name.
     *
     * @param  string $string
     * @return boolean
     */
    public static function isXName($string)
    {
        return (bool)preg_match('(^[Xx]-[A-Za-z\d\-]+$)S', $string);
    }

    /**
     * Add a calendar.
     *
     * @param  Component\Calendar $calendar
     * @return void
     */
    public function addCalendar(Component\Calendar $calendar)
    {
        $this->calendars[] = $calendar;
    }

    /**
     * Get a calendar with a specific index.
     *
     * Usually, an Ical object will only consist of a single calendar, so the
     * default value for $index is 0.
     *
     * @param  integer $index
     * @return Component\Calendar
     */
    public function getCalendar($index = 0)
    {
        if (isset($this->calendars[$index])) {
            return $this->calendars[$index];
        }

        return null;
    }
}
