<?php

namespace Ipunkt\Calendar\Ical\Component;

use Ipunkt\Calendar\Ical\Exception;
use Ipunkt\Calendar\Ical\Ical;

/**
 * Timezone component.
 */
class Timezone extends AbstractComponent
{
    /**
     * Aliases for timezones which are not in the TZ database anymore.
     *
     * @var array
     */
    protected static $timezoneAliases = array(
        'Asia/Katmandu' => 'Asia/Kathmandu',
        'Asia/Calcutta' => 'Asia/Kolkata',
        'Asia/Saigon' => 'Asia/Ho_Chi_Minh',
        'Africa/Asmera' => 'Africa/Asmara',
        'Africa/Timbuktu' => 'Africa/Bamako',
        'Atlantic/Faeroe' => 'Atlantic/Faroe',
        'Atlantic/Jan_Mayen' => 'Europe/Oslo',
        'America/Argentina/ComodRivadavia' => 'America/Argentina/Catamarca',
        'America/Louisville' => 'America/Kentucky/Louisville',
        'Europe/Belfast' => 'Europe/London',
        'Pacific/Yap' => 'Pacific/Truk',
    );
    /**
     * Offsets.
     *
     * @var array
     */
    protected $offsets = array();

    /**
     * Create a Timezone component from a timezone ID.
     *
     * @param  string $timezoneId
     * @return Timezone
     */
    public static function fromTimezoneId($timezoneId)
    {
        if (isset(self::$timezoneAliases[$timezoneId])) {
            $filename = self::$timezoneAliases[$timezoneId];
        } else {
            $filename = $timezoneId;
        }

        $ical = Ical::fromUri(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'Timezones' . DIRECTORY_SEPARATOR . $filename . '.ics');
        $timezone = $ical->getCalendar()->getTimezone($filename);

        if ($timezone->getPropertyValue('TZID') !== $timezoneId) {
            $timezone->properties()->get('TZID')->setText($timezoneId);
        }

        return $timezone;
    }

    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VTIMEZONE';
    }

    /**
     * Add an offset to the timezone.
     *
     * @param  AbstractOffsetComponent $offset
     * @return self
     */
    public function addOffset(AbstractOffsetComponent $offset)
    {
        $this->offsets[] = $offset;

        return $this;
    }

    /**
     * Get offsets.
     *
     * @return array
     */
    public function getOffsets()
    {
        return $this->offsets;
    }

    /**
     * Set offsets.
     *
     * $offsets must either be an instance of 'Standard' or 'Daylight', or an
     * array consisting of at least one 'Standard' and 'Daylight' component.
     *
     * @param  mixed $offsets
     * @return self
     * @throws Exception\InvalidArgumentException
     */
    public function setOffsets($offsets)
    {
        if ($offsets instanceof AbstractOffsetComponent) {
            $offsets = array($offsets);
        } elseif (!is_array($offsets)) {
            throw new Exception\InvalidArgumentException('Offset is no instance of AbstractOffsetComponent, nor an array');
        }

        $this->offsets = array();

        foreach ($offsets as $offset) {
            if (!$offsets instanceof AbstractOffsetComponent) {
                throw new Exception\InvalidArgumentException('Offset is no instance of AbstractOffsetComponent');
            }

            $this->offsets[] = $offset;
        }

        return $this;
    }
}