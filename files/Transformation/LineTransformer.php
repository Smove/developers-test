<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Transformation;

use Jan\DevelopersTest\Transformation\Exception\InvalidRowException;

/**
 * An interface for a simple line transformer, which can be used for our FileProcessor
 */
interface LineTransformer
{
    /**
     * Transform the input columns for output file
     *
     * @param string[] $values
     * @return string[]|null
     * @throws InvalidRowException
     */
    public function transform(array $values): ?array;
}
