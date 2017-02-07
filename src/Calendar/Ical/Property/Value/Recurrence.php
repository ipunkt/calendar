<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

use Ipunkt\Calendar\Ical\Exception;

/**
 * Recurrence value.
 */
class Recurrence implements Value
{
    /**
     * Allowed frequencies.
     *
     * @var array
     */
    protected static $frequencies = [
        'SECONDLY',
        'MINUTELY',
        'HOURLY',
        'DAILY',
        'WEEKLY',
        'MONTHLY',
        'YEARLY'
    ];
    /**
     * Allowed weekdays.
     *
     * @var array
     */
    protected static $weekdays = [
        'SU' => 1,
        'MO' => 2,
        'TU' => 3,
        'WE' => 4,
        'TH' => 5,
        'FR' => 6,
        'SA' => 7
    ];
    /**
     * Frequency.
     *
     * @var string
     */
    protected $frequency;
    /**
     * Until.
     *
     * @var DateTime
     */
    protected $until;
    /**
     * Count.
     *
     * @var integer
     */
    protected $count;
    /**
     * Interval.
     *
     * @var integer
     */
    protected $interval = 1;
    /**
     * By second.
     *
     * @var array
     */
    protected $bySecond = [];
    /**
     * By minute.
     *
     * @var array
     */
    protected $byMinute = [];
    /**
     * By hour.
     *
     * @var array
     */
    protected $byHour = [];
    /**
     * By day.
     *
     * @var array
     */
    protected $byDay = [];
    /**
     * By month day.
     *
     * @var array
     */
    protected $byMonthDay = [];
    /**
     * By year day.
     *
     * @var array
     */
    protected $byYearDay = [];
    /**
     * By week no.
     *
     * @var array
     */
    protected $byWeekNo = [];
    /**
     * By month.
     *
     * @var array
     */
    protected $byMonth = [];
    /**
     * By set pos.
     *
     * @var array
     */
    protected $bySetPos = [];
    /**
     * Week start.
     *
     * @var string
     */
    protected $weekStart = 'MO';

    /**
     * Create a new recurrence value.
     *
     * @param  string $frequency
     */
    public function __construct($frequency)
    {
        $this->setFrequency($frequency);
    }

    /**
     * fromString(): defined by Value interface.
     *
     * @see    Value::fromString()
     * @param  string $string
     * @return Value
     */
    public static function fromString($string)
    {
        $values = [];
        $parts = explode(';', $string);

        foreach ($parts as $part) {
            $data = explode('=', $part, 2);

            if (!isset($data[1])) {
                return null;
            }

            $name = strtoupper($data[0]);
            $value = $data[1];

            switch ($name) {
                case 'FREQ':
                case 'UNTIL':
                case 'COUNT':
                case 'INTERVAL':
                case 'WKST':
                    $values[$name] = $value;
                    break;

                case 'BYSECOND':
                case 'BYMINUTE':
                case 'BYHOUR':
                case 'BYDAY':
                case 'BYMONTHDAY':
                case 'BYYEARDAY':
                case 'BYWEEKNO':
                case 'BYMONTH':
                case 'BYSETPOS':
                    $values[$name] = explode(',', $value);
                    break;

                default:
                    return null;
            }
        }

        if (!isset($values['FREQ'])) {
            return null;
        }

        try {
            $self = new self($values['FREQ']);

            foreach ($values as $name => $value) {
                switch ($name) {
                    case 'UNTIL':
                        $self->setUntil($value);
                        break;

                    case 'COUNT':
                        $self->setCount($value);
                        break;

                    case 'INTERVAL':
                        $self->setInterval($value);
                        break;

                    case 'WKST':
                        $self->setWeekStart($value);
                        break;

                    case 'BYSECOND':
                        $self->setBySecond($value);
                        break;

                    case 'BYMINUTE':
                        $self->setByMinute($value);
                        break;

                    case 'BYHOUR':
                        $self->setByHour($value);
                        break;

                    case 'BYDAY':
                        $self->setByDay($value);
                        break;

                    case 'BYMONTHDAY':
                        $self->setByMonthDay($value);
                        break;

                    case 'BYYEARDAY':
                        $self->setByYearDay($value);
                        break;

                    case 'BYWEEKNO':
                        $self->setByWeekNo($value);
                        break;

                    case 'BYMONTH':
                        $self->setByMonth($value);
                        break;

                    case 'BYSETPOS':
                        $self->setBySetPos($value);
                        break;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return $self;
    }

    /**
     * Get frequency.
     *
     * @return string
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set frequency.
     *
     * @param  string $frequency
     * @return self
     * @throws Exception\InvalidArgumentException when frequency value is not valid
     */
    public function setFrequency($frequency)
    {
        $frequency = strtoupper($frequency);

        if (!in_array($frequency, self::$frequencies)) {
            throw new Exception\InvalidArgumentException(sprintf('Frequency value "%s" is not valid', $frequency));
        }

        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get interval.
     *
     * @return integer
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set interval.
     *
     * @param  integer|null $interval
     * @return self
     * @throws Exception\InvalidArgumentException when interval is not an integer
     */
    public function setInterval($interval = null)
    {
        if ($interval === null) {
            $this->interval = 1;
        } else {
            if (!is_numeric($interval)) {
                throw new Exception\InvalidArgumentException(sprintf('Interval must be an integer, "%s" received',
                    $interval));
            }

            $this->interval = max(1, (int)$interval);
        }

        return $this;
    }

    /**
     * Get until.
     *
     * @return DateTime
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * Set until.
     *
     * @param  DateTime|string|null $until
     * @return self
     * @throws Exception\RuntimeException when until should be set without having count
     * @throws Exception\InvalidArgumentException when until does not match a DateTime value
     */
    public function setUntil($until = null)
    {
        if ($until === null) {
            $this->until = null;
        } elseif ($this->count !== null) {
            throw new Exception\RuntimeException('Until cannot be set while Count is set');
        } else {
            if (!$until instanceof DateTime) {
                $until = DateTime::fromString($until);

                if ($until === null) {
                    throw new Exception\InvalidArgumentException('Until does not match a DateTime value');
                }
            }

            $this->until = $until;
        }

        return $this;
    }

    /**
     * Get count.
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set count.
     *
     * @param  integer|null $count
     * @return self
     * @throws Exception\RuntimeException when count should be set without having until empty
     * @throws Exception\InvalidArgumentException when count is not an integer
     */
    public function setCount($count = null)
    {
        if ($count === null) {
            $this->count = null;
        } elseif ($this->until !== null) {
            throw new Exception\RuntimeException('Count cannot be set while Until is set');
        } else {
            if (!is_numeric($count)) {
                throw new Exception\InvalidArgumentException(sprintf('Count must be an integer, "%s" received',
                    $count));
            }

            $this->count = max(1, (int)$count);
        }

        return $this;
    }

    /**
     * Get week end.
     *
     * @param  boolean $numeric
     * @return string
     */
    public function getWeekStart($numeric = false)
    {
        if ($numeric) {
            return self::$weekdays[$this->weekStart];
        } else {
            return $this->weekStart;
        }
    }

    /**
     * Set week start.
     *
     * @param  string|null $weekStart
     * @return self
     * @throws Exception\InvalidArgumentException when weekday is not valid
     */
    public function setWeekStart($weekStart = null)
    {
        if ($weekStart === null) {
            $this->weekStart = null;
        } else {
            $weekStart = strtoupper($weekStart);

            if (!in_array($weekStart, self::$weekdays)) {
                throw new Exception\InvalidArgumentException(sprintf('Weekday value "%s" is not valid', $weekStart));
            }

            $this->weekStart = $weekStart;
        }

        return $this;
    }

    /**
     * Get by second.
     *
     * @return array
     */
    public function getBySecond()
    {
        return $this->bySecond;
    }

    /**
     * Set by second.
     *
     * @param  array|null $bySecond
     * @return self
     * @throws Exception\InvalidArgumentException when second is not valid
     */
    public function setBySecond(array $bySecond = null)
    {
        if ($bySecond === null) {
            $this->bySecond = [];
        } else {
            $values = [];

            foreach ($bySecond as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('BySecond values must be integers, "%s" received',
                        $value));
                } elseif ($value < 0) {
                    throw new Exception\InvalidArgumentException(sprintf('BySecond value "%s" is lower than 0',
                        $value));
                } elseif ($value > 60) {
                    throw new Exception\InvalidArgumentException(sprintf('BySecond value "%s" is greater than 60',
                        $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('BySecond values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->bySecond = $values;
        }

        return $this;
    }

    /**
     * Get by minute.
     *
     * @return array
     */
    public function getByMinute()
    {
        return $this->byMinute;
    }

    /**
     * Set by minute.
     *
     * @param  array|null $byMinute
     * @return self
     * @throws Exception\InvalidArgumentException when minute is not valid
     */
    public function setByMinute(array $byMinute = null)
    {
        if ($byMinute === null) {
            $this->byMinute = [];
        } else {
            $values = [];

            foreach ($byMinute as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMinute values must be integers, "%s" received',
                        $value));
                } elseif ($value < 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMinute value "%s" is lower than 0',
                        $value));
                } elseif ($value > 59) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMinute value "%s" is greater than 59',
                        $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByMinute values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->byMinute = $values;
        }

        return $this;
    }

    /**
     * Get by hour.
     *
     * @return array
     */
    public function getByHour()
    {
        return $this->byHour;
    }

    /**
     * Set by hour.
     *
     * @param  array|null $byHour
     * @return self
     * @throws Exception\InvalidArgumentException when hour is not valid
     */
    public function setByHour(array $byHour = null)
    {
        if ($byHour === null) {
            $this->byHour = [];
        } else {
            $values = [];

            foreach ($byHour as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByHour values must be integers, "%s" received',
                        $value));
                } elseif ($value < 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByHour value "%s" is lower than 0', $value));
                } elseif ($value > 23) {
                    throw new Exception\InvalidArgumentException(sprintf('ByHour value "%s" is greater than 23',
                        $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByHour values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->byHour = $values;
        }

        return $this;
    }

    /**
     * Get by day.
     *
     * @param  bool $numeric
     * @return array
     */
    public function getByDay($numeric = false)
    {
        if ($numeric) {
            $values = [];

            foreach ($this->byDay as $byDay) {
                $values[] = array($byDay[0], self::$weekdays[$byDay[1]]);
            }

            return $values;
        } else {
            return $this->byDay;
        }
    }

    /**
     * Set by day.
     *
     * @param  array|null $byDay
     * @return self
     * @throws Exception\InvalidArgumentException when day is not valid
     */
    public function setByDay(array $byDay = null)
    {
        if ($byDay === null) {
            $this->byDay = [];
        } else {
            $values = [];

            foreach ($byDay as $value) {
                $value = strtoupper($value);

                if (!preg_match('(^((?:[+-]?)\d{1,2})?([A-Z]{2})?$)S', $value, $match)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is not valid', $value));
                }

                $value = array((int)$match[1], $match[2]);

                if ($value[0] < -53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is lower than -53',
                        $value[0]));
                } elseif ($value[0] > 53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is greater than 53',
                        $value[0]));
                } elseif (!isset(self::$weekdays[$value[1]])) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is not valid', $value[1]));
                }

                $values[] = $value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByDay values must contain at least one element');
            }

            $this->byDay = $values;
        }

        return $this;
    }

    /**
     * Get by month day.
     *
     * @return array
     */
    public function getByMonthDay()
    {
        return $this->byMonthDay;
    }

    /**
     * Set by month day.
     *
     * @param  array|null $byMonthDay
     * @return self
     * @throws Exception\InvalidArgumentException when month is not valid
     */
    public function setByMonthDay(array $byMonthDay = null)
    {
        if ($byMonthDay === null) {
            $this->byMonthDay = [];
        } else {
            $values = [];

            foreach ($byMonthDay as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonthDay values must be integers, "%s" received',
                        $value));
                } elseif ($value < -31) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonthDay value "%s" is lower than -31',
                        $value));
                } elseif ($value > 31) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonthDay value "%s" is greater than 31',
                        $value));
                } elseif ($value == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonthDay value "%s" is 0', $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByMonthDay values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->byMonthDay = $values;
        }

        return $this;
    }

    /**
     * Get by year day.
     *
     * @return array
     */
    public function getByYearDay()
    {
        return $this->byYearDay;
    }

    /**
     * Set by year day.
     *
     * @param  array|null $byYearDay
     * @return self
     * @throws Exception\InvalidArgumentException when year is not valid
     * @TODO check bounds for leap year and not leap year
     */
    public function setByYearDay(array $byYearDay = null)
    {
        if ($byYearDay === null) {
            $this->byYearDay = [];
        } else {
            $values = [];

            foreach ($byYearDay as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay values must be integers, "%s" received',
                        $value));
                } elseif ($value < -366) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay value "%s" is lower than -366',
                        $value));
                } elseif ($value > 366) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay value "%s" is greater than 366',
                        $value));
                } elseif ($value == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay value "%s" is 0', $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByYearDay values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->byYearDay = $values;
        }

        return $this;
    }

    /**
     * Get by week no.
     *
     * @return array
     */
    public function getByWeekNo()
    {
        return $this->byWeekNo;
    }

    /**
     * Set by week no.
     *
     * @param  array|null $byWeekNo
     * @return self
     * @throws Exception\InvalidArgumentException when week number is not valid
     */
    public function setByWeekNo(array $byWeekNo = null)
    {
        if ($byWeekNo === null) {
            $this->byWeekNo = [];
        } else {
            $values = [];

            foreach ($byWeekNo as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo values must be integers, "%s" received',
                        $value));
                } elseif ($value < -53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo value "%s" is lower than -53',
                        $value));
                } elseif ($value > 53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo value "%s" is greater than 53',
                        $value));
                } elseif ($value == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo value "%s" is 0', $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByWeekNo values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->byWeekNo = $values;
        }

        return $this;
    }

    /**
     * Get by month.
     *
     * @return array
     */
    public function getByMonth()
    {
        return $this->byMonth;
    }

    /**
     * Set by month.
     *
     * @param  array|null $byMonth
     * @return self
     * @throws Exception\InvalidArgumentException when month is not valid
     */
    public function setByMonth(array $byMonth = null)
    {
        if ($byMonth === null) {
            $this->byMonth = [];
        } else {
            $values = [];

            foreach ($byMonth as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonth values must be integers, "%s" received',
                        $value));
                } elseif ($value < 1) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonth value "%s" is lower than 1', $value));
                } elseif ($value > 12) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonth value "%s" is greater than 12',
                        $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByMonth values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->byYearDay = $values;
        }

        return $this;
    }

    /**
     * Get by set pos.
     *
     * @return array
     */
    public function getBySetPos()
    {
        return $this->bySetPos;
    }

    /**
     * Set by set pos.
     *
     * @param array $bySetPos
     * @return self
     * @throws Exception\InvalidArgumentException when pos is not valid
     */
    public function setBySetPos(array $bySetPos = null)
    {
        if ($bySetPos === null) {
            $this->bySetPos = [];
        } else {
            $values = [];

            foreach ($bySetPos as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos values must be integers, "%s" received',
                        $value));
                } elseif ($value < -366) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos value "%s" is lower than -366',
                        $value));
                } elseif ($value > 366) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos value "%s" is greater than 366',
                        $value));
                } elseif ($value == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos value "%s" is 0', $value));
                }

                $values[] = (int)$value;
            }

            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('BySetPos values must contain at least one element');
            }

            sort($values, SORT_NUMERIC);

            $this->bySetPos = $values;
        }

        return $this;
    }

    /**
     * Get a recurrence iterator.
     *
     * @param  DateTime $dateTimeStart
     * @return RecurrenceIterator
     */
    public function getIterator(DateTime $dateTimeStart)
    {
        return new RecurrenceIterator($this, $dateTimeStart);
    }
}
