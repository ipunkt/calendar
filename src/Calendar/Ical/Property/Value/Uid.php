<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

/**
 * UID value.
 */
class Uid implements Value
{
    /**
     * UID.
     *
     * @var string
     */
    protected $uid;

    /**
     * Create a new UID value.
     *
     * @param  string|null $uid
     */
    public function __construct($uid = null)
    {
        if ($uid === null) {
            $this->generateNewUid();
        } else {
            $this->setUid($uid);
        }
    }

    /**
     * Create a new value from a string.
     *
     * @param  string $string
     * @return Value
     */
    public static function fromString($string)
    {
        return new self($string);
    }

    /**
     * Generate a new UID.
     *
     * @return self
     */
    public function generateNewUid()
    {
        $this->uid = gmdate('Ymd') . 'T' . gmdate('His') . 'Z-' . uniqid('', true) . '@' . gethostname();

        return $this;
    }

    /**
     * Get UID.
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set UID.
     *
     * @param  string $uid
     * @return self
     */
    public function setUid($uid)
    {
        $this->uid = (string)$uid;

        return $this;
    }
}