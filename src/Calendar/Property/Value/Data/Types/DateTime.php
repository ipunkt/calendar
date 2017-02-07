<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types;

/**
 * Class DateTime
 *
 * Purpose:  This value type is used to identify values that specify a
 * precise calendar date and time of day.
 *
 * Format Definition:  This value type is defined by the following
 * notation:
 *
 * date-time  = date "T" time ;As specified in the DATE and TIME
 * ;value definitions
 *
 * Description:  If the property permits, multiple "DATE-TIME" values
 * are specified as a COMMA-separated list of values.  No additional
 * content value encoding (i.e., BACKSLASH character encoding, see
 * Section 3.3.11) is defined for this value type.
 *
 * The "DATE-TIME" value type is used to identify values that contain
 * a precise calendar date and time of day.  The format is based on
 * the [ISO.8601.2004] complete representation, basic format for a
 * calendar date and time of day.  The text format is a concatenation
 * of the "date", followed by the LATIN CAPITAL LETTER T character,
 * the time designator, followed by the "time" format.
 *
 * The "DATE-TIME" value type expresses time values in three forms:
 *
 * The form of date and time with UTC offset MUST NOT be used.  For
 * example, the following is not valid for a DATE-TIME value:
 *
 * 19980119T230000-0800       ;Invalid time format
 *
 * FORM #1: DATE WITH LOCAL TIME
 *
 * The date with local time form is simply a DATE-TIME value that
 * does not contain the UTC designator nor does it reference a time
 * zone.  For example, the following represents January 18, 1998, at
 * 11 PM:
 *
 * 19980118T230000
 *
 * DATE-TIME values of this type are said to be "floating" and are
 * not bound to any time zone in particular.  They are used to
 * represent the same hour, minute, and second value regardless of
 * which time zone is currently being observed.  For example, an
 * event can be defined that indicates that an individual will be
 * busy from 11:00 AM to 1:00 PM every day, no matter which time zone
 * the person is in.  In these cases, a local time can be specified.
 * The recipient of an iCalendar object with a property value
 * consisting of a local time, without any relative time zone
 * information, SHOULD interpret the value as being fixed to whatever
 * time zone the "ATTENDEE" is in at any given moment.  This means
 * that two "Attendees", in different time zones, receiving the same
 * event definition as a floating time, may be participating in the
 * event at different actual times.  Floating time SHOULD only be
 * used where that is the reasonable behavior.
 *
 * In most cases, a fixed time is desired.  To properly communicate a
 * fixed time in a property value, either UTC time or local time with
 * time zone reference MUST be specified.
 *
 * The use of local time in a DATE-TIME value without the "TZID"
 * property parameter is to be interpreted as floating time,
 * regardless of the existence of "VTIMEZONE" calendar components in
 * the iCalendar object.
 *
 * FORM #2: DATE WITH UTC TIME
 *
 * The date with UTC time, or absolute time, is identified by a LATIN
 * CAPITAL LETTER Z suffix character, the UTC designator, appended to
 * the time value.  For example, the following represents January 19,
 * 1998, at 0700 UTC:
 *
 * 19980119T070000Z
 *
 * The "TZID" property parameter MUST NOT be applied to DATE-TIME
 * properties whose time values are specified in UTC.
 *
 * FORM #3: DATE WITH LOCAL TIME AND TIME ZONE REFERENCE
 *
 * The date and local time with reference to time zone information is
 * identified by the use the "TZID" property parameter to reference
 * the appropriate time zone definition.  "TZID" is discussed in
 * detail in Section 3.2.19.  For example, the following represents
 * 2:00 A.M. in New York on January 19, 1998:
 *
 * TZID=America/New_York:19980119T020000
 *
 * If, based on the definition of the referenced time zone, the local
 * time described occurs more than once (when changing from daylight
 * to standard time), the DATE-TIME value refers to the first
 * occurrence of the referenced time.  Thus, TZID=America/
 * New_York:20071104T013000 indicates November 4, 2007 at 1:30 A.M.
 * EDT (UTC-04:00).  If the local time described does not occur (when
 * changing from standard to daylight time), the DATE-TIME value is
 * interpreted using the UTC offset before the gap in local times.
 * Thus, TZID=America/New_York:20070311T023000 indicates March 11,
 * 2007 at 3:30 A.M. EDT (UTC-04:00), one hour after 1:30 A.M. EST
 * (UTC-05:00).
 * A time value MUST only specify the second 60 when specifying a
 * positive leap second.  For example:
 *
 * 19970630T235960Z
 *
 * Implementations that do not support leap seconds SHOULD interpret
 * the second 60 as equivalent to the second 59.
 *
 * Example:  The following represents July 14, 1997, at 1:30 PM in New
 * York City in each of the three time formats, using the "DTSTART"
 * property.
 *
 * DTSTART:19970714T133000                   ; Local time
 * DTSTART:19970714T173000Z                  ; UTC time
 * DTSTART;TZID=America/New_York:19970714T133000
 * ; Local time and time
 * ; zone reference
 */
class DateTime extends Date
{
    const FORMAT = 'Ymd?His';
    const FORMAT_DATE = 'Ymd';
    const FORMAT_TIME = 'His';
    const SEPARATOR = 'T';
    const UTC_TIMEZONE = 'UTC';
    const UTC_TIMEZONE_IDENTIFIER = 'Z';

    /**
     * creates a type value from string
     *
     * @param string $string
     * @return BaseType
     */
    public static function createFromString($string)
    {
        $pattern = '~TZID=(.*?)\:([\d]{8,8}T[\d]{6,6})~';
        if (preg_match($pattern, $string, $matches)) {
            return new self(\DateTime::createFromFormat(self::FORMAT, $matches[2], new \DateTimeZone($matches[1])));
        } elseif (substr($string, -1) === self::UTC_TIMEZONE_IDENTIFIER) {
            $value = \DateTime::createFromFormat(self::FORMAT, substr($string, 0, -1),
                new \DateTimeZone(self::UTC_TIMEZONE));
        } else {
            $value = \DateTime::createFromFormat(self::FORMAT, $string);
        }

        return new self($value);
    }

    /**
     * sets a datetime value
     *
     * @param \DateTime|string $value
     * @return $this
     */
    public function setValue($value)
    {
        if (!$value instanceof \DateTime) {
            $pattern = '~TZID=(.*?)\:([\d]{8,8}T[\d]{6,6})~';
            if (preg_match($pattern, $value, $matches)) {
                $value = \DateTime::createFromFormat(self::FORMAT, $matches[2], new \DateTimeZone($matches[1]));
            } elseif (substr($value, -1) === self::UTC_TIMEZONE_IDENTIFIER) {
                $value = \DateTime::createFromFormat(self::FORMAT, substr($value, 0, -1),
                    new \DateTimeZone(self::UTC_TIMEZONE));
            } else {
                $value = \DateTime::createFromFormat(self::FORMAT, $value);
            }
        }

        $this->value = $value;

        return $this;
    }

    /**
     * represents a string representation
     *
     * @return string
     */
    public function getStringValue()
    {
        if ($this->defaultValue === $this->getValue()) {
            return '';
        }

        if (empty($this->value)) {
            return '';
        }

        if ($this->value->getTimezone()->getName() !== @date_default_timezone_get()) {
            if ($this->value->getTimezone()->getName() === self::UTC_TIMEZONE) {
                return $this->value->format(self::FORMAT_DATE) . self::SEPARATOR . $this->value->format(self::FORMAT_TIME) . self::UTC_TIMEZONE_IDENTIFIER;
            }

            return 'TZID=' . $this->value->getTimezone()->getName() . ':' . $this->value->format(self::FORMAT_DATE) . self::SEPARATOR . $this->value->format(self::FORMAT_TIME);
        }

        return $this->value->format(self::FORMAT_DATE) . self::SEPARATOR . $this->value->format(self::FORMAT_TIME);
    }
}