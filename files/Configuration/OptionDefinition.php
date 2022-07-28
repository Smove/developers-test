<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Configuration;

/**
 * A definition of a option that can be used with our csv processor
 */
class OptionDefinition
{
    public const TYPE_NOVALUE = '';
    public const TYPE_REQUIREDVALUE = ':';
    public const TYPE_OPTIONALVALUE = '::';

    protected string $type;
    protected ?string $short;

    /**
     * @param string $type Value type of option
     * @param string $name Name of the option
     * @param string|null $short Short option character
     * @param string|null $default
     */
    public function __construct(string $type, protected string $name, ?string $short = null, protected ?string $default = null)
    {
        assert($type === self::TYPE_NOVALUE || $type === self::TYPE_REQUIREDVALUE || $type === self::TYPE_OPTIONALVALUE, '$type must correspond to one of the TYPE_* constants');
        assert(strlen($short) === 1, '$short can only be a single char');
        $this->type = $type;
        $this->short = $short;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getShort(): ?string
    {
        return $this->short;
    }

    /**
     * @return string|null
     */
    public function getDefault(): ?string
    {
        return $this->default;
    }
}
