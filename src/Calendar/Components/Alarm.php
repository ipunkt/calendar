<?php

namespace Ipunkt\Calendar\Components;

class Alarm
{
    protected $type = 'VALARM';
    protected $properties = [
        'audioprop' => null,
        'dispprop' => null,
        'emailprop' => null,
    ];
}