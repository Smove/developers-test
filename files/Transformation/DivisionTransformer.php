<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Transformation;

use Jan\DevelopersTest\Transformation\Exception\InvalidRowException;

class DivisionTransformer extends AbstractMathTransformer
{
    protected function validateColumns(string $firstNumber, string $secondNumber): void
    {
        if (((int)$secondNumber) === 0) {
            throw new InvalidRowException('Cannot divide by 0');
        }
    }

    protected function getResult(int $firstNumber, int $secondNumber): float
    {
        return $firstNumber / $secondNumber;
    }
}
