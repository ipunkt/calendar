<?php

namespace Ipunkt\Calendar\Ical\Property;

use Ipunkt\Calendar\Ical\Property\Value\Value;

/**
 * Property.
 */
class Property
{
    /**
     * Property value type aliases.
     *
     * @var array
     */
    protected static $propertyValueTypeAlias = [
        'DATE' => 'DateTime',
    ];
    /**
     * Property value types.
     *
     * If a property supports multiple value types, the first one is the default.
     *
     * @var array
     */
    protected static $propertyNameToValueTypeMap = [
        // Calendar properties
        'VERSION' => ['Text'],
        'PRODID' => ['Text'],
        'CALSCALE' => ['Text'],
        'METHOD' => ['Text'],

        // Descriptive properties
        'ATTACH' => ['Uri', 'Binary'],
        'CATEGORIES' => ['Text'],
        'CLASS' => ['Text'],
        'COMMENT' => ['Text'],
        'DESCRIPTION' => ['Text'],
        'GEO' => ['Geo'],
        'LOCATION' => ['Text'],
        'PERCENT-COMPLETE' => ['Integer'],
        'PRIORITY' => ['Integer'],
        'RESOURCES' => ['Text'],
        'STATUS' => ['Text'],
        'SUMMARY' => ['Text'],

        // Date and time properties
        'COMPLETED' => ['DateTime'],
        'DTEND' => ['DateTime'],
        'DUE' => ['DateTime'],
        'DTSTART' => ['DateTime'],
        'DURATION' => ['Duration'],
        'FREEBUSY' => ['Period'],
        'TRANSP' => ['Text'],

        // Timezone properties
        'TZID' => ['Text'],
        'TZNAME' => ['Text'],
        'TZOFFSETFROM' => ['UtcOffset'],
        'TZOFFSETTO' => ['UtcOffset'],
        'TZURL' => ['Uri'],

        // Relationship properties
        'ATTENDEE' => ['CalAddress'],
        'CONTACT' => ['Text'],
        'ORGANIZER' => ['CalAddress'],
        'RECURRENCE-ID' => ['DateTime'],
        'RELATED-TO' => ['Text'],
        'URL' => ['Uri'],
        'UID' => ['Text'],

        // Recurrence properties
        'EXDATE' => ['DateTime'],
        'RDATE' => ['DateTime', 'Period'],
        'RRULE' => ['Recurrence'],

        // Alarm properties
        'ACTION' => ['Text'],
        'REPEAT' => ['Integer'],
        'TRIGGER' => ['Duration', 'DateTime'],

        // Change managment properties
        'CREATED' => ['DateTime'],
        'DTSTAMP' => ['DateTime'],
        'LAST-MODIFIED' => ['DateTime'],
        'SEQUENCE' => ['Integer'],

        // Miscellaneous properties
        'REQUEST-STATUS' => ['Text'],
    ];
    /**
     * Name of the property.
     *
     * @var string
     */
    protected $name;
    /**
     * Property value.
     *
     * @var Value
     */
    protected $value;
    /**
     * Property parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Create a new property.
     *
     * @param  string $name
     */
    public function __construct($name)
    {
        $this->name = strtoupper($name);
    }

    /**
     * returns the value type alias if defined
     *
     * @param string $valueType
     * @return string
     */
    public static function getValueTypeAlias($valueType)
    {
        if (isset(self::$propertyValueTypeAlias[$valueType])) {
            return self::$propertyValueTypeAlias[$valueType];
        }

        return $valueType;
    }

    /**
     * Get value types from property name.
     *
     * @param  string $name
     * @return array
     */
    public static function getValueTypesFromName($name)
    {
        if (!isset(self::$propertyNameToValueTypeMap[$name])) {
            return array('Raw');
        }

        return self::$propertyNameToValueTypeMap[$name];
    }

    /**
     * Get the property name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get property value.
     *
     * @return Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set property value.
     *
     * @param  Value $value
     * @return self
     */
    public function setValue(Value $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Set a parameter.
     *
     * @param  string $name
     * @param  Value $value
     * @return self
     */
    public function setParameter($name, Value $value)
    {
        $name = strtoupper($name);

        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * Remove a parameter.
     *
     * @param  string $name
     * @return self
     */
    public function removeParameter($name)
    {
        $name = strtoupper($name);

        if (isset($this->parameters[$name])) {
            unset($this->parameters[$name]);
        }

        return $this;
    }

    /**
     * Get a parameter.
     *
     * @param  string $name
     * @return Value
     */
    public function getParameter($name)
    {
        $name = strtoupper($name);

        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        return null;
    }
}