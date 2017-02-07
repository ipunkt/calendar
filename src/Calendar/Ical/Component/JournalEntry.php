<?php

namespace Ipunkt\Calendar\Ical\Component;

/**
 * Journal entry component.
 */
class JournalEntry extends AbstractComponent
{
    /**
     * getName(): defined by AbstractComponent.
     *
     * @see    AbstractComponent::getName()
     * @return string
     */
    public function getName()
    {
        return 'VJOURNAL';
    }
}
