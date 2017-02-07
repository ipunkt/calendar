<?php

namespace Ipunkt\Calendar\Components;

class Timezone
{
    protected $type = 'VTIMEZONE';
    protected $properties = [
        'tzid' => null,
        'last-mod' => null,
        'tz-url' => null,
        'standardc' => null,
        'daylightc' => null,
    ];
}