<?php

namespace Ipunkt\Calendar;

use Ipunkt\Calendar\Components\Calendar as CalendarComponent;

class Calendar
{
    const LIST_VALUE_SEPARATOR = ',';
    const MULTIPLE_PART_SEPARATOR = ';';
    const VALUE_QUOTATION = '"';
    const END_OF_LINE = "\n";
    const LINE_GLUE = "\r\n";

    /**
     * list of calendars
     *
     * @var CalendarComponent[]
     */
    protected $calendars = array();

    /**
     * index of the current calendar
     *
     * @var int
     */
    protected $currentCalendar = 0;

    /**
     * Creates an instance
     */
    public function __construct()
    {
        $this->addCalendar();
    }

    /**
     * returns the current calendar component
     *
     * @return CalendarComponent
     */
    public function getCalender()
    {
        return $this->calendars[$this->currentCalendar];
    }

    /**
     * adds a calendar and returns it
     *
     * @return CalendarComponent
     */
    public function addCalendar()
    {
        $this->calendars[] = new CalendarComponent();
        $this->currentCalendar = count($this->calendars) - 1;
        return $this->getCalender();
    }
}