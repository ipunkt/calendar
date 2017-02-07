<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types;

use Ipunkt\Calendar\Property\Value\Data\Types\Duration\Date;
use Ipunkt\Calendar\Property\Value\Data\Types\Duration\Time;
use Ipunkt\Calendar\Property\Value\Data\Types\Duration\Week;

/**
 * Class Duration
 *
 * Purpose:  This value type is used to identify properties that contain
 * a duration of time.
 *
 * Format Definition:  This value type is defined by the following
 * notation:
 *
 * dur-value  = (["+"] / "-") "P" (dur-date / dur-time / dur-week)
 *
 * dur-date   = dur-day [dur-time]
 * dur-time   = "T" (dur-hour / dur-minute / dur-second)
 * dur-week   = 1*DIGIT "W"
 * dur-hour   = 1*DIGIT "H" [dur-minute]
 * dur-minute = 1*DIGIT "M" [dur-second]
 * dur-second = 1*DIGIT "S"
 * dur-day    = 1*DIGIT "D"
 *
 * Description:  If the property permits, multiple "duration" values are
 * specified by a COMMA-separated list of values.  The format is
 * based on the [ISO.8601.2004] complete representation basic format
 * with designators for the duration of time.  The format can
 * represent nominal durations (weeks and days) and accurate
 * durations (hours, minutes, and seconds).  Note that unlike
 * [ISO.8601.2004], this value type doesn't support the "Y" and "M"
 * designators to specify durations in terms of years and months.
 *
 * The duration of a week or a day depends on its position in the
 * calendar.  In the case of discontinuities in the time scale, such
 * as the change from standard time to daylight time and back, the
 * computation of the exact duration requires the subtraction or
 * addition of the change of duration of the discontinuity.  Leap
 * seconds MUST NOT be considered when computing an exact duration.
 * When computing an exact duration, the greatest order time
 * components MUST be added first, that is, the number of days MUST
 * be added first, followed by the number of hours, number of
 * minutes, and number of seconds.
 *
 * Negative durations are typically used to schedule an alarm to
 * trigger before an associated time (see Section 3.8.6.3).
 *
 * No additional content value encoding (i.e., BACKSLASH character
 * encoding, see Section 3.3.11) are defined for this value type.
 *
 * Example:  A duration of 15 days, 5 hours, and 20 seconds would be:
 *
 * P15DT5H0M20S
 *
 * A duration of 7 weeks would be:
 *
 * P7W
 */
class Duration extends BaseType
{
    /**
     * negative
     *
     * @var bool
     */
    protected $isNegative = false;

    /**
     * creates a Duration instance
     *
     * @param null|Duration\Date|Duration\Time|Duration\Week $value
     * @param null $defaultValue
     */
    public function __construct($value = null, $defaultValue = null)
    {
        $this->value = array(
            'dur-date' => new Duration\Date(),
            'dur-time' => new Duration\Time(),
            'dur-week' => new Duration\Week(),
        );

        if (null !== $value) {
            $this->setValue($value);
        }

        $this->defaultValue = $defaultValue;
    }

    /**
     * creates a Duration from string
     *
     * @param string $string
     * @return BaseType|Duration
     * @throws \Exception
     */
    public static function createFromString($string)
    {
        $pattern = '~([+-]?)P(([\d]+)D)?(T([\d]+)H([\d]+)M([\d]+)S)?(([\d]+)W)?~';
        if (!preg_match($pattern, $string, $matches)) {
            throw new \Exception('Given string is not a valid pattern for Duration.');
        }

        $duration = new self();

        if (isset($matches[1]) && $matches[1] === '-') {
            $duration->setNegative();
        }

        if (isset($matches[3])) {
            $date = new Duration\Date();

            $day = new Duration\Day($matches[3]);
            $date->setValue($day);

            $duration->setValue($date);
        }

        if (isset($matches[5]) || isset($matches[6]) || isset($matches[7])) {
            $time = new Duration\Time();

            if (isset($matches[5])) {
                $hour = new Duration\Hour($matches[5]);
                $time->setValue($hour);
            }
            if (isset($matches[6])) {
                $minute = new Duration\Minute($matches[6]);
                $time->setValue($minute);
            }
            if (isset($matches[7])) {
                $second = new Duration\Second($matches[7]);
                $time->setValue($second);
            }

            $duration->setValue($time);
        }

        if (isset($matches[9])) {
            $week = new Duration\Week($matches[9]);
            $duration->setValue($week);
        }

        return $duration;
    }

    /**
     * sets negative
     *
     * @param bool $negative
     * @return $this
     */
    public function setNegative($negative = true)
    {
        $this->isNegative = $negative;

        return $this;
    }

    /**
     * sets value
     *
     * @param Duration\Date|Duration\Time|Duration\Week $value
     * @return $this
     */
    public function setValue($value)
    {
        $key = null;
        switch (get_class($value)) {
            case Date::class:
                $key = 'dur-date';
                break;

            case Time::class:
                $key = 'dur-time';
                break;

            case Week::class:
                $key = 'dur-week';
                break;
        }

        if (isset($this->value[$key])) {
            $this->value[$key] = $value;
        }

        return $this;
    }

    /**
     * returns date part
     *
     * @return Duration\Date
     */
    public function getDate()
    {
        return $this->value['dur-date'];
    }

    /**
     * returns time part
     *
     * @return Duration\Time
     */
    public function getTime()
    {
        return $this->value['dur-time'];
    }

    /**
     * returns week part
     *
     * @return Duration\Week
     */
    public function getWeek()
    {
        return $this->value['dur-week'];
    }

    /**
     * returns a string representation
     *
     * @return string
     */
    public function getStringValue()
    {
        if ($this->defaultValue === $this->getValue()) {
            return '';
        }

        return ($this->isNegative ? '-' : '') . 'P' . implode('', $this->value);
    }
}