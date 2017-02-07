<?php

namespace Ipunkt\Calendar\Writer;

class CalendarWriter
{
    /**
     * events
     *
     * @var CalendarEvent[]
     */
    protected $events;

    /**
     * title
     *
     * @var string
     */
    protected $title;

    /**
     * author
     *
     * @var string
     */
    protected $author;

    protected $description = '';

    protected $ttlInMinutes = 10;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $parameters += [
            'events' => [],
            'title' => 'Calendar',
            'author' => 'Calender Generator'
        ];
        $this->events = $parameters['events'];
        $this->title = $parameters['title'];
        $this->author = $parameters['author'];
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * set author
     *
     * @param string $author
     *
     * @return CalendarWriter
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * adds an event
     *
     * @param CalendarEvent $event
     * @return CalendarWriter
     */
    public function addEvent(CalendarEvent $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * @return \Ipunkt\Calendar\Writer\CalendarEvent[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * set events
     *
     * @param \Ipunkt\Calendar\Writer\CalendarEvent[] $events
     *
     * @return CalendarWriter
     */
    public function setEvents($events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * set title
     *
     * @param string $title
     *
     * @return CalendarWriter
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * returns Description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * sets description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * returns TtlInMinutes
     *
     * @return int
     */
    public function getTtlInMinutes(): int
    {
        return $this->ttlInMinutes;
    }

    /**
     * sets ttlInMinutes
     *
     * @param int $ttlInMinutes
     * @return $this
     */
    public function setTtlInMinutes($ttlInMinutes)
    {
        $this->ttlInMinutes = intval($ttlInMinutes);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $content = 'BEGIN:VCALENDAR' . "\r\n"
            . 'VERSION:2.0' . "\r\n"
            . 'PRODID:-//' . $this->author . '//NONSGML//EN' . "\r\n"
            . 'X-WR-CALNAME:' . $this->title . "\r\n"
            . 'X-WR-CALDESC:' . $this->description . "\r\n"
            . 'X-WR-TIMEZONE:UTC' . "\r\n"
            . 'CALSCALE:GREGORIAN' . "\r\n"
            . 'METHOD:PUBLISH' . "\r\n"
            . 'X-PUBLISHED-TTL:' . $this->ttlInMinutes . "\r\n";    //update interval in minutes

        foreach ($this->events as $event) {
            $content .= (string)$event;
        }

        $content .= 'END:VCALENDAR';

        return $content;
    }

    /**
     * sends the calendar with download
     * @param string $filename
     */
    public function sendAsDownload($filename = 'calendar.ics')
    {
        $generated = $this->__toString();

        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); //date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); //tell it we just updated
        header('Cache-Control: no-store, no-cache, must-revalidate'); //force re-validation
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Description: File Transfer');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($generated));
        print $generated;
    }
}