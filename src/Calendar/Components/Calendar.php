<?php

namespace Ipunkt\Calendar\Components;

use Ipunkt\Calendar\Property\CalendarScale;
use Ipunkt\Calendar\Property\Method;
use Ipunkt\Calendar\Property\ProductIdentifier;
use Ipunkt\Calendar\Property\Version;

class Calendar extends BaseComponent
{
    protected $type = 'VCALENDAR';
    protected $properties = [
        'prodid' => null,
        'version' => null,
        'calscale' => null,
        'method' => null,
    ];

    public function __construct($productIdentifier = null)
    {
        $this->properties['prodid'] = new ProductIdentifier($productIdentifier);
        $this->properties['version'] = new Version();
        $this->properties['calscale'] = new CalendarScale();
        $this->properties['method'] = new Method();
    }
}