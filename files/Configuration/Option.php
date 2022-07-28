<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Configuration;

/**
 * A single parsed option
 */
class Option extends OptionDefinition
{
    protected ?string $value = null;

    /**
     * @param OptionDefinition $optionDefinition
     * @param string|null $value
     */
    public function __construct(OptionDefinition $optionDefinition, ?string $value = null)
    {
        parent::__construct($optionDefinition->getType(), $optionDefinition->getName(), $optionDefinition->getShort(), $optionDefinition->getDefault());
        if ($this->type !== self::TYPE_NOVALUE) {
            $this->value = $value;
        }
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
