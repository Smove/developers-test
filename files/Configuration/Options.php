<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Configuration;

/**
 * A set of 0-n Option objects which is filled from \getopt method
 */
class Options
{
    /**
     * @param OptionDefinition[] $availableOptions
     */
    public static function getFromPhpOpts(array $availableOptions): Options
    {
        $shortOpts = implode('', array_filter(array_map(static fn (OptionDefinition $availableOption) => $availableOption->getShort() ? $availableOption->getShort() . $availableOption->getType() : null, $availableOptions)));
        $longOpts =  array_map(static fn (OptionDefinition $availableOption) => $availableOption->getName() . $availableOption->getType(), $availableOptions);

        $rawOptions = getopt($shortOpts, $longOpts);

        $options = new Options();
        foreach ($availableOptions as $availableOption) {
            $rawValue = $rawOptions[$availableOption->getShort()] ?? ($rawOptions[$availableOption->getName()] ?? false);
            if (($useRawValue = ($rawValue !== false)) || $availableOption->getDefault() !== null) {
                $options->addOption(new Option($availableOption, $useRawValue ? $rawValue : $availableOption->getDefault()));
            }
        }

        return $options;
    }

    /** @var Option[] */
    protected array $options = [];

    protected function addOption(Option $option): void
    {
        $this->options[$option->getName()] = $option;
    }

    public function hasOption(string $optionName): bool
    {
        return array_key_exists($optionName, $this->options);
    }

    public function getOption(string $optionName): ?Option
    {
        return $this->options[$optionName] ?? null;
    }

    public function getOptionValue(string $optionName): ?string
    {
        return $this->hasOption($optionName) ? $this->getOption($optionName)->getValue() : null;
    }
}
