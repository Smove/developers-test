<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Io\Exception;

/**
 * This exception should be thrown if a required file is not writeable
 */
class FileNotWriteableException extends FileException
{
    public function __construct(string $path)
    {
        parent::__construct('File "' . $path . '" is not writeable.');
    }
}
