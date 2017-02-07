<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * Todo component.
 */
class Todo extends AbstractComponent
{
    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VTODO';
    }
}
