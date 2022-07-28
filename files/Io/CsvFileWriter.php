<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Io;

use Jan\DevelopersTest\Io\Exception\FileException;

/**
 * File writer with custom csv methods
 */
class CsvFileWriter extends FileWriter
{
    /**
     * @throws FileException
     */
    public function writeCsvRow(array $fields)
    {
        if ($this->handle === null) {
            $this->open();
        }
        fputcsv($this->handle, $fields, ';');
    }
}
