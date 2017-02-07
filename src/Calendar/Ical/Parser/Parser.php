<?php

namespace Ipunkt\Calendar\Ical\Parser;

use Ipunkt\Calendar\Ical\Component;
use Ipunkt\Calendar\Ical\Exception;
use Ipunkt\Calendar\Ical\Ical;
use Ipunkt\Calendar\Ical\Property\Property;
use Ipunkt\Calendar\Ical\Property\Value;

/**
 * Ical parser based on libical.
 */
class Parser
{
    /**
     * Component types.
     */
    const COMPONENT_NONE = 0;
    const COMPONENT_VENDOR = 1;
    const COMPONENT_IANA = 2;

    const BASE_NAMESPACE = '\Ipunkt\Calendar\Ical';
    /**
     * Stream resource.
     *
     * @var resource
     */
    protected $stream;
    /**
     * Buffer for getting folded lines.
     *
     * @var string
     */
    protected $buffer;
    /**
     * Raw data of the current line.
     *
     * @var string
     */
    protected $rawData;
    /**
     * Current position in the current line.
     *
     * @var integer
     */
    protected $currentPos;
    /**
     * Ical object.
     *
     * @var Ical
     */
    protected $ical;
    /**
     * Regular expressions used in the parser.
     *
     * @var array
     */
    protected $regex;
    /**
     * Component stack.
     *
     * @var \SplStack
     */
    protected $components;

    /**
     * Create a new lexer with an open file stream.
     *
     * @param  resource $stream
     * @throws Exception\InvalidArgumentException when no strem given
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new Exception\InvalidArgumentException('Stream must be a resource');
        }

        $this->stream = $stream;

        // Base regex types
        $this->regex = array(
            'iana-token' => '[A-Za-z\d\-]+',
            'x-name' => '[Xx]-[A-Za-z\d\-]+',
            'safe-char' => '[\x20\x09\x21\x23-\x2B\x2D-\x39\x3C-\x7E\x80-\xFB]',
            'qsafe-char' => '[\x20\x09\x21\x23-\x7E\x80-\xFB]',
            'tsafe-char' => '[\x20\x21\x23-\x2B\x2D-\x39\x3C-\x5B\x5D-\x7E\x80-\xFB]',
            'value-char' => '[\x20\x09\x21-\x7E\x80-\xFB]',
            'escaped-char' => '(?:\\\\|\\;|\\,|\\\\N|\\\\n)'
        );

        // Regex types based on base type
        $this->regex['param-text'] = '(' . $this->regex['safe-char'] . '*)';
        $this->regex['quoted-string'] = '"(' . $this->regex['qsafe-char'] . '*)"';
        $this->regex['name'] = '(?:' . $this->regex['x-name'] . '|' . $this->regex['iana-token'] . ')';
        $this->regex['param-name'] = '(?:(' . $this->regex['x-name'] . ')|(' . $this->regex['iana-token'] . '))';
        $this->regex['param-value'] = '(?:' . $this->regex['quoted-string'] . '|' . $this->regex['param-text'] . ')';
        $this->regex['text'] = '((?:' . $this->regex['tsafe-char'] . '|' . $this->regex['escaped-char'] . '|[:"])*)';
        $this->regex['value'] = $this->regex['value-char'] . '*';
    }

    /**
     * Parse the input from the stream.
     *
     * @return Ical
     * @throws Exception\ParseException when input stream ends unexpected
     */
    public function parse()
    {
        $this->ical = new Ical();
        $this->components = new \SplStack();

        while (null !== ($this->rawData = $this->getNextUnfoldedLine())) {
            $this->parseLine();
        }

        if (count($this->components) > 0) {
            throw new Exception\ParseException('Unexpected end in input stream');
        }

        return $this->ical;
    }

    /**
     * Get the next unfolded line from the stream.
     *
     * @return string
     */
    protected function getNextUnfoldedLine()
    {
        if (feof($this->stream)) {
            return null;
        }

        $rawData = $this->buffer . fgets($this->stream);

        while (
            !feof($this->stream) && ($this->buffer = fgetc($this->stream))
            && ($this->buffer === ' ' || $this->buffer === "\t")
        ) {
            $rawData = rtrim($rawData, "\r\n") . fgets($this->stream);
            $this->buffer = '';
        }

        return $rawData;
    }

    /**
     * Parse a single line.
     *
     * @throws Exception\ParseException when parsing fails
     * @throws Exception\NotImplementedException when property unknown
     */
    protected function parseLine()
    {
        $this->currentPos = 0;
        $propertyName = $this->getPropertyName();

        // If the property name is BEGIN or END, we are actually starting or
        // ending a new component.
        if ($propertyName === 'BEGIN') {
            $this->handleComponentBegin();
            return;
        } elseif ($propertyName === 'END') {
            $this->handleComponentEnd();
            return;
        }

        // At this point, the property name really is a property name (Not a
        // component name), so make a new property and add it to the component.
        if (count($this->components) === 0) {
            throw new Exception\ParseException('Found property outside of a component');
        }

        $property = new Property($propertyName);
        $currentComponent = $this->components->top();

        if ($currentComponent instanceof Component\Experimental || $currentComponent instanceof Component\Iana) {
            $valueTypes = null;
        } else {
            $valueTypes = Property::getValueTypesFromName($propertyName);
        }

        // Handle parameter values
        while ($this->rawData[$this->currentPos - 1] !== ':') {
            $parameterName = $this->getNextParameterName();
            $parameterValue = $this->getNextParameterValue();

            $property->setParameter($parameterName, new Value\Text($parameterValue));
        }

        // Handle property values
        if ($valueTypes === null) {
            // Contents of experimental and IANA components are treated as raw values.
            $value = new Value\Raw($this->getValue());
        } else {
            // Check if an alternate value type is specified
            $valueType = $property->getParameter('VALUE');

            if ($valueType !== null) {
                if ($valueType instanceof Value\Text) {
                    $valueType = strtoupper($valueType->getText());
                }

                //	Alias-Handling
                $valueType = Property::getValueTypeAlias($valueType);

                if (!in_array($valueType, $valueTypes)) {
                    throw new Exception\ParseException(sprintf('A disallowed value type "%s" was specified for property %',
                        $valueType, $propertyName));
                }
            } else {
                $valueType = $valueTypes[0];
            }

            switch ($propertyName) {
                case 'CATEGORIES':
                case 'RESOURCES':
                case 'RDATE':
                case 'EXDATE':
                    // @TODO implement Value\Multi()
                    /*
                    $value = new Value\Multi();

                    do {
                        $value->add($this->getPropertyValue($propertyName, $this->getNextValue(), $valueType));
                    } while ($this->rawData[$this->currentPos - 1] === ',');
                    */
                    #throw new Exception\NotImplementedException(sprintf('Property %s is not implemented yet', $propertyName));
                    return;
                    break;

                default:
                    $value = $this->createPropertyValue($propertyName, $this->getValue(), $valueType);
                    break;
            }
        }

        $this->components->top()->properties()->add($property->setValue($value));
    }

    /**
     * Get a property name.
     *
     * @return string
     * @throws Exception\ParseException when no property nor BEGIN or END tag found
     */
    protected function getPropertyName()
    {
        if (!preg_match(
            '(\G(?<name>' . $this->regex['name'] . ')[;:])S',
            $this->rawData, $match, 0, $this->currentPos
        )
        ) {
            throw new Exception\ParseException('Could not find a property name, component BEGIN or END tag');
        }

        $this->currentPos += strlen($match[0]);

        return strtoupper($match['name']);
    }

    /**
     * Handle the beginning of a component.
     *
     * @return void
     * @throws Exception\ParseException when no valid component name found
     */
    protected function handleComponentBegin()
    {
        $currentComponent = null;
        $componentName = strtoupper($this->getValue());

        if (count($this->components) > 0) {
            $currentComponent = $this->components->top();

            if ($currentComponent instanceof Component\Experimental || $currentComponent instanceof Component\Iana) {
                if (Ical::isXName($componentName)) {
                    $componentType = Component\AbstractComponent::COMPONENT_EXPERIMENTAL;
                } elseif (Ical::isIanaToken($componentName)) {
                    $componentType = Component\AbstractComponent::COMPONENT_IANA;
                } else {
                    $componentType = Component\AbstractComponent::COMPONENT_NONE;
                }
            } else {
                $componentType = Component\AbstractComponent::getTypeFromName($componentName);
            }
        } else {
            $componentType = Component\AbstractComponent::getTypeFromName($componentName);
        }

        if ($componentType === Component\AbstractComponent::COMPONENT_NONE) {
            throw new Exception\ParseException(sprintf('"%s" is not a valid component name', $componentName));
        } elseif ($componentType === Component\AbstractComponent::COMPONENT_EXPERIMENTAL) {
            $component = new Component\Experimental($componentName);
        } elseif ($componentType === Component\AbstractComponent::COMPONENT_IANA) {
            $component = new Component\Iana($componentName);
        } else {
            $className = self::BASE_NAMESPACE . '\Component\\' . $componentType;
            $component = new $className();
        }

        if ($componentType === 'Calendar') {
            if (count($this->components) > 0) {
                throw new Exception\ParseException('VCALENDAR component found inside another component');
            }

            /** @var Component\Calendar $component */
            $this->ical->addCalendar($component);
        } else {
            if (count($this->components) === 0) {
                throw new Exception\ParseException(sprintf('%s Component found outside of VCALENDAR component',
                    $componentName));
            }

            // Assume that it could be added to the current component, and set
            // it to false if it cold not.
            $addedToComponent = true;

            if ($currentComponent instanceof Component\Experimental
                || $currentComponent instanceof Component\Iana
            ) {
                if ($componentType === Component\AbstractComponent::COMPONENT_EXPERIMENTAL) {
                    $currentComponent->addExperimentalComponent($component);
                } elseif ($currentComponent === Component\AbstractComponent::COMPONENT_IANA) {
                    $currentComponent->addIanaComponent($component);
                } else {
                    $addedToComponent = false;
                }
            } else {
                switch ($currentComponent->getName()) {
                    case 'VEVENT':
                    case 'VTODO':
                        if ($component instanceof Component\Alarm) {
                            $currentComponent->addAlarm($component);
                        } else {
                            $addedToComponent = false;
                        }
                        break;

                    case 'VTIMEZONE':
                        if ($component instanceof Component\AbstractOffsetComponent) {
                            $currentComponent->addOffset($component);
                        } else {
                            $addedToComponent = false;
                        }
                        break;

                    case 'VCALENDAR':
                        switch ($componentType) {
                            case 'Timezone':
                                $currentComponent->addTimezone($component);
                                break;

                            case 'Event':
                                $currentComponent->addEvent($component);
                                break;

                            case 'Todo':
                                $currentComponent->addTodo($component);
                                break;

                            case 'JournalEntry':
                                $currentComponent->addJournalEntry($component);
                                break;

                            case 'FreeBusyTime':
                                $currentComponent->addFreeBusyTime($component);
                                break;

                            case Component\AbstractComponent::COMPONENT_EXPERIMENTAL:
                                $currentComponent->addExperimentalComponent($component);
                                break;

                            case Component\AbstractComponent::COMPONENT_IANA:
                                $currentComponent->addIanaComponent($component);
                                break;

                            default:
                                $addedToComponent = false;
                                break;
                        }
                        break;

                    default:
                        $addedToComponent = false;
                        break;
                }
            }

            if (!$addedToComponent) {
                throw new Exception\ParseException(sprintf('%s component found inside %s component', $componentName,
                    $currentComponent->getName()));
            }
        }

        $this->components->push($component);
    }

    /**
     * Get the value of the current property.
     *
     * @return string
     * @throws Exception\ParseException when no property value found
     */
    protected function getValue()
    {
        if (!preg_match(
            '(\G(?<value>' . $this->regex['value'] . ')\r?\n?)S',
            $this->rawData, $match, 0, $this->currentPos
        )
        ) {
            throw new Exception\ParseException('Could not find a property value');
        }

        $this->currentPos += strlen($match[0]);

        return $match['value'];
    }

    /**
     * Handle the ending of a component.
     *
     * @return void
     * @throws Exception\ParseException when ending tag has no begin or does not match current component
     */
    protected function handleComponentEnd()
    {
        if (count($this->components) === 0) {
            throw new Exception\ParseException('Found component ending tag without a beginning tag');
        }

        $currentComponent = $this->components->pop();
        $componentName = strtoupper($this->getValue());

        if ($componentName !== $currentComponent->getName()) {
            throw new Exception\ParseException(sprintf('Ending tag does not match current component'));
        }
    }

    /**
     * Get the next parameter name.
     *
     * @return string
     * @throws Exception\ParseException when no parameter name found
     */
    protected function getNextParameterName()
    {
        if (!preg_match(
            '(\G(?<name>' . $this->regex['name'] . ')=)S',
            $this->rawData, $match, 0, $this->currentPos
        )
        ) {
            throw new Exception\ParseException('Could not find a parameter name');
        }

        $this->currentPos += strlen($match[0]);

        return $match['name'];
    }

    /**
     * Get the next parameter value.
     *
     * @return string
     * @throws Exception\ParseException when no parameter value found
     */
    protected function getNextParameterValue()
    {
        if (!preg_match(
            '(\G(?<value>' . $this->regex['param-value'] . ')[,:])S',
            $this->rawData, $match, 0, $this->currentPos
        )
        ) {
            throw new Exception\ParseException('Could not find a parameter value');
        }

        $this->currentPos += strlen($match[0]);

        return $match['value'];
    }

    /**
     * Get the next value of a property.
     *
     * A property may have multiple values, if the values are separated by
     * commas in the content line.
     *
     * @return string
     * @throws Exception\ParseException when no next property value found
     * @todo   Handle escaped commas properly
     */
    protected function getNextValue()
    {
        if (!preg_match(
            '(\G(?<value>' . $this->regex['value'] . ')(?:,|\r?\n?))S',
            $this->rawData, $match, 0, $this->currentPos
        )
        ) {
            throw new Exception\ParseException('Could not find next property value');
        }

        $this->currentPos += strlen($match[0]);

        return $match['value'];
    }

    /**
     * Create a property value.
     *
     * @param  string $propertyName
     * @param  string $string
     * @param  string $valueType
     * @return Value\Value
     * @throws Exception\ParseException when property does not match type
     */
    protected function createPropertyValue($propertyName, $string, $valueType)
    {
        $value = null;
        $className = self::BASE_NAMESPACE . '\Property\Value\\' . $valueType;

        //  PHP7 Notice: Classes does not have a name like Float
        if ($className === 'Ipunkt\Calendar\Property\Value\Data\Types\Float') {
            $className = 'Ipunkt\Calendar\Property\Value\Data\Types\FloatType';
        }

        /** @var Value\Value $className */
        if (null === ($value = $className::fromString($string))) {
            throw new Exception\ParseException(sprintf('Value of property %s doesn\'t match %s type', $propertyName,
                $valueType));
        }

        return $value;
    }
}
