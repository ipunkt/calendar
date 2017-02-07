<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

class CalendarScale extends Text
{
    /**
     * Set calendar scale.
     *
     * @param  string $calendarScale
     * @return self
     */
    public function setCalendarScale($calendarScale)
    {
        $this->text = (string)$calendarScale;

        return $this;
    }

    /**
     * Get calendar scale.
     *
     * @return string
     */
    public function getCalendarScale()
    {
        return $this->text;
    }
}