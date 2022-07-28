<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Transformation;

class PlusTransformer extends AbstractMathTransformer
{
    protected function getResult(int $firstNumber, int $secondNumber): int
    {
        return $firstNumber + $secondNumber;
    }
}
