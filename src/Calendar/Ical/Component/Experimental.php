<?php

namespace Ipunkt\Calendar\Ical\Component;

use Ipunkt\Calendar\Ical\Exception;
use Ipunkt\Calendar\Ical\Ical;

/**
 * Experimental component.
 */
class Experimental extends AbstractCustomContainerComponent
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
     * @throws Exception\InvalidArgumentException when no valid x-name given
     */
    public function __construct($name)
    {
        if (!Ical::isXName($name)) {
            throw new Exception\InvalidArgumentException(sprintf('"%s" is not a valid x-name', $name));
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
