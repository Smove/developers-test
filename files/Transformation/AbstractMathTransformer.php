<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Transformation;

use Jan\DevelopersTest\Transformation\Exception\InvalidRowException;

abstract class AbstractMathTransformer implements LineTransformer
{
    /**
     * @inheritDoc
     */
    public function transform(array $values): ?array
    {
        if (($columnCount = count($values)) !== 2) {
            throw new InvalidRowException(sprintf('Row has invalid column count %d', $columnCount));
        }
        [$firstNumber, $secondNumber] = $values;
        if (!is_numeric($firstNumber) || !is_numeric($secondNumber)) {
            throw new InvalidRowException(sprintf('Column value is no number: %s, %s', $firstNumber, $secondNumber));
        }

        $this->validateColumns($firstNumber, $secondNumber);

        $result = $this->getResult((int)$firstNumber, (int)$secondNumber);

        if ($result === null || $result <= 0) {
            throw new InvalidRowException(sprintf('Numbers %d and %d are wrong', $firstNumber, $secondNumber));
        }

        return [$firstNumber, $secondNumber, (string)$result];
    }

    /**
     * This method can be overridden if any custom column/number validation is required
     *
     * @throws InvalidRowException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function validateColumns(string $firstNumber, string $secondNumber): void
    {
    }

    /**
     * Returns the result of $firstNumber and $secondNumber for this transformer
     *
     * @param int $firstNumber First column/number
     * @param int $secondNumber Second column/number
     * @return int|float|null The calculated result. NULL if not possible to calculate (which should never be possible, because such values should fail at method validateColumns)
     */
    abstract protected function getResult(int $firstNumber, int $secondNumber): int|float|null;
}
