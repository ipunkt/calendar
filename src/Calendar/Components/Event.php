<?php

namespace Ipunkt\Calendar\Components;

class Event extends BaseComponent
{
    protected $type = 'VEVENT';
    protected $properties = [
        'dtstamp' => null,
        'uid' => null,
        'dtstart' => null,
        'class' => null,
        'created' => null,
        'description' => null,
        'geo' => null,
        'last-mod' => null,
        'location' => null,
        'organizer' => null,
        'priority' => null,
        'seq' => null,
        'status' => null,
        'summary' => null,
        'transp' => null,
        'url' => null,
        'recurid' => null,
        'rrule' => null,
        'dtend' => null,
        'duration' => null,
        'attach' => null,
        'attendee' => null,
        'categories' => null,
        'comment' => null,
        'contact' => null,
        'exdate' => null,
        'rstatus' => null,
        'related' => null,
        'resources' => null,
        'rdate' => null,
    ];
}