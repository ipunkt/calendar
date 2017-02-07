<?php

namespace Ipunkt\Calendar\Property\Value\Data\Types;

use Ipunkt\Calendar\Property\Value\Data\Types\Time\Hour;
use Ipunkt\Calendar\Property\Value\Data\Types\Time\Minute;
use Ipunkt\Calendar\Property\Value\Data\Types\Time\Second;

class UtcOffset extends BaseType
{
    /**
     * negative
     *
     * @var bool
     */
    protected $isNegative = false;

    /**
     * creates an UtcOffset instance
     *
     * @param null|Time\Hour|Time\Minute|Time\Second $value
     * @param null $defaultValue
     */
    public function __construct($value = null, $defaultValue = null)
    {
        $this->value = array(
            'time-hour' => new Time\Hour(),
            'time-minute' => new Time\Minute(),
            'time-second' => new Time\Second(),
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
        $pattern = '~([+-])([\d]{2,2})([\d]{2,2})([\d]{2,2})?~';
        if (!preg_match($pattern, $string, $matches)) {
            throw new \Exception('Given string is not a valid pattern for UtcOffset.');
        }

        $offset = new self();

        if (isset($matches[1]) && $matches[1] === '-') {
            $offset->setNegative();
        }

        if (isset($matches[2])) {
            $hour = new Time\Hour($matches[2]);
            $offset->setValue($hour);
        }
        if (isset($matches[3])) {
            $minute = new Time\Minute($matches[3]);
            $offset->setValue($minute);
        }
        if (isset($matches[4])) {
            $second = new Time\Second($matches[4]);
            $offset->setValue($second);
        }

        return $offset;
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
            case Hour::class:
                $key = 'time-hour';
                break;

            case Minute::class:
                $key = 'time-minute';
                break;

            case Second::class:
                $key = 'time-second';
                break;
        }

        if (isset($this->value[$key])) {
            $this->value[$key] = $value;
        }

        return $this;
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
     * returns Hour component
     *
     * @return Time\Hour
     */
    public function getHour()
    {
        return $this->value['time-hour'];
    }

    /**
     * returns Minute component
     *
     * @return Time\Minute
     */
    public function getMinute()
    {
        return $this->value['time-minute'];
    }

    /**
     * returns Second component
     *
     * @return Time\Second
     */
    public function getSecond()
    {
        return $this->value['time-second'];
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

        return ($this->isNegative ? '-' : '+') . implode('', $this->value);
    }
}