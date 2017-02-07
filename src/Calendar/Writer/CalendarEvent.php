<?php

namespace Ipunkt\Calendar\Writer;

use Carbon\Carbon;
use DateTime;

class CalendarEvent
{
    /**
     *
     * The event ID
     * @var string
     */
    private $uid;

    /**
     * The event start date
     * @var Carbon
     */
    private $start;

    /**
     * The event end date
     * @var Carbon
     */
    private $end;

    /**
     * created at
     * @var Carbon
     */
    private $created;

    /**
     * updated at
     * @var Carbon
     */
    private $updated;

    /**
     *
     * The event title
     * @var string
     */
    private $summary;

    /**
     * The event description
     * @var string
     */
    private $description;

    /**
     * The event location
     * @var string
     */
    private $location;

    /**
     * contact name
     * @var string
     */
    private $contact;

    /**
     * whole day event
     * @var bool
     */
    private $wholeday;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $parameters += array(
            'summary' => 'Untitled Event',
            'description' => '',
            'location' => '',
            'contact' => '',
        );

        if (isset($parameters['uid'])) {
            $this->uid = $parameters['uid'];
        } else {
            $this->uid = uniqid(rand(0, getmypid()));
        }
        $this->start = $parameters['start'];
        $this->end = $parameters['end'];
        $this->summary = $parameters['summary'];
        $this->description = $parameters['description'];
        $this->location = $parameters['location'];

        if (!isset($parameters['created'])) {
            $parameters['created'] = new DateTime();
        }
        $this->created = $parameters['created'];

        if (!isset($parameters['updated'])) {
            $parameters['updated'] = new DateTime();
        }
        $this->updated = $parameters['updated'];

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * set description
     *
     * @param string $description
     *
     * @return CalendarEvent
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * set end
     *
     * @param Carbon $end
     *
     * @return CalendarEvent
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * set location
     *
     * @param string $location
     *
     * @return CalendarEvent
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * set start
     *
     * @param Carbon $start
     *
     * @return CalendarEvent
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * set summary
     *
     * @param string $summary
     *
     * @return CalendarEvent
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * set uid
     *
     * @param string $uid
     *
     * @return CalendarEvent
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * set created
     *
     * @param Carbon $created
     *
     * @return CalendarEvent
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * set updated
     *
     * @param Carbon $updated
     *
     * @return CalendarEvent
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * set contact
     *
     * @param string $contact
     *
     * @return CalendarEvent
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getWholeday()
    {
        return $this->wholeday;
    }

    /**
     * set wholeday
     *
     * @param boolean|null $wholeday
     *
     * @return CalendarEvent
     */
    public function setWholeday($wholeday)
    {
        $this->wholeday = $wholeday;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $content = "UID:{$this->uid}\r\n"
            . "{$this->formatDate($this->start, 'DTSTART')}\r\n"
            . "{$this->formatDate($this->end, 'DTEND')}\r\n"
            . "{$this->formatDate($this->start, 'DTSTAMP')}\r\n"
            . "{$this->formatDate($this->created, 'CREATED')}\r\n"
            . "SUMMARY:{$this->formatValue($this->summary)}\r\n"
            . "{$this->formatDate($this->updated, 'LAST-MODIFIED')}\r\n";

        if (!empty($this->description)) {
            $content .= "DESCRIPTION:{$this->formatValue($this->description)}\r\n";
        }
        if (!empty($this->location)) {
            $content .= "LOCATION:{$this->location}\r\n";
        }
        if (!empty($this->contact)) {
            $content .= "CONTACT:{$this->contact}\r\n";
        }
        if ($this->wholeday !== null) {
            $content .= 'X-FUNAMBOL-ALLDAY:' . (($this->wholeday) ? '1' : '0') . "\r\n"
                . 'X-MICROSOFT-CDO-ALLDAYEVENT:' . (($this->wholeday) ? 'TRUE' : 'FALSE') . "\r\n";
        }

        $content .= "SEQUENCE:0\r\n"
            . "STATUS:CONFIRMED\r\n"
            . "TRANSP:OPAQUE\r\n";

        return "BEGIN:VEVENT\r\n" . $content . "END:VEVENT\r\n";
    }

    /**
     * Get the start time set for the even
     * @param Carbon $date
     * @param string|null $prefix
     * @return string
     */
    private function formatDate(Carbon $date, $prefix = null)
    {
        if ($prefix === null) {
            return ($date->local) ? $date->format('Ymd\\THis') : $date->format('Ymd\\THis\\Z');
        }

        $result = $prefix;
        if ($date->local) {
            //	Local Time
            $result .= ';TZID=' . $date->tzName . ':' . $date->format('Ymd\\THis');
        } else {
            //	UTC
            $result .= ':' . $date->format('Ymd\\THis\\Z');
        }

        return $result;
    }

    /**
     * Escape commas, semi-colons, backslashes.
     *
     * @see http://stackoverflow.com/questions/1590368/should-a-colon-character-be-escaped-in-text-values-in-icalendar-rfc2445
     *
     * @param $str
     * @return string
     */
    private function formatValue($str)
    {
        return addcslashes($str, ",\\;");
    }
}