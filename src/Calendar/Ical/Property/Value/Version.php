<?php

namespace Ipunkt\Calendar\Ical\Property\Value;

class Version extends Text
{
    /**
     * Create a new version value.
     *
     * @param  string $version
     */
    public function __construct($version = '2.0')
    {
        parent::__construct($version);
    }

    /**
     * Set version.
     *
     * @param  string $version
     * @return self
     */
    public function setVersion($version)
    {
        $this->setText($version);

        return $this;
    }

    /**
     * Get version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->getText();
    }
}