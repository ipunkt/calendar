<?php

namespace Ipunkt\Calendar\Ical\Component;

use Ipunkt\Calendar\Ical\Ical;
use Ipunkt\Calendar\Ical\Property\PropertyList;
use Ipunkt\Calendar\Ical\Property\Value;

/**
 * Abstract component.
 */
abstract class AbstractComponent
{
    /**
     * Component types.
     */
    const COMPONENT_NONE = 0;
    const COMPONENT_EXPERIMENTAL = 1;
    const COMPONENT_IANA = 2;
    /**
     * Map of component names to component types.
     *
     * @var array
     */
    protected static $nameToTypeMap = [
        'VCALENDAR' => 'Calendar',
        'VALARM' => 'Alarm',
        'VTIMEZONE' => 'Timezone',
        'STANDARD' => 'OffsetStandard',
        'DAYLIGHT' => 'OffsetDaylight',
        'VEVENT' => 'Event',
        'VTODO' => 'Todo',
        'VJOURNAL' => 'JournalEntry',
        'VFREEBUSY' => 'FreeBusyTime'
    ];
    /**
     * Properties.
     *
     * @var PropertyList
     */
    protected $properties;

    /**
     * Create a new component.
     */
    public function __construct()
    {
        $this->properties = new PropertyList();
    }

    /**
     * Get component type from name.
     *
     * @param string $name
     * @return int
     */
    public static function getTypeFromName($name)
    {
        if (!isset(self::$nameToTypeMap[$name])) {
            if (Ical::isXName($name)) {
                return self::COMPONENT_EXPERIMENTAL;
            } elseif (Ical::isIanaToken($name)) {
                return self::COMPONENT_IANA;
            } else {
                return self::COMPONENT_NONE;
            }
        }

        return self::$nameToTypeMap[$name];
    }

    /**
     * Get the iCalendar conforming component name.
     *
     * It is important that the returned name is uppercase.
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Get the value of a single instance property.
     *
     * @param  string $name
     * @return mixed
     */
    public function getPropertyValue($name)
    {
        $property = $this->properties()->get($name);

        if ($property === null) {
            return null;
        }

        $value = $property->getValue();

        if ($value instanceof Value\Text) {
            return $value->getText();
        }

        return null;
    }

    /**
     * Get all properties.
     *
     * @return PropertyList
     */
    public function properties()
    {
        return $this->properties;
    }
}
