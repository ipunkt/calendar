<?php

namespace Ipunkt\Calendar\Components;

class FreeBusy
{
    protected $type = 'VFREEBUSY';
    protected $properties = [
        'dtstamp' => null,
        'uid' => null,
        'contact' => null,
        'dtstart' => null,
        'dtend' => null,
        'organizer' => null,
        'url' => null,
        'attendee' => null,
        'comment' => null,
        'freebusy' => null,
        'rstatus' => null,
    ];
}