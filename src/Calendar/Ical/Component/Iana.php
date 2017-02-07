<?php

namespace Ipunkt\Calendar\Ical\Component;

use Ipunkt\Calendar\Ical\Ical,
	Ipunkt\Calendar\Ical\Exception;

/**
 * IANA component.
 */
class Iana extends AbstractCustomContainerComponent
{
    /**
     * Component name.
     *
     * @var string
     */
    protected $name;

    /**
     * __construct(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::__construct()
     * @param  string $name
     * @throws Exception\InvalidArgumentException when no valid IANA token given
     */
    public function __construct($name)
    {
        if (!Ical::isIanaToken($name)) {
            throw new Exception\InvalidArgumentException(sprintf('"%s" is not a valid IANA token', $name));
        }

        $this->name = strtoupper($name);

        parent::__construct();
    }

    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
