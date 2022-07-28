<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Io\Exception;

/**
 * This exception should be thrown if the required file cannot be found
 */
class FileNotFoundException extends FileException
{
    public function __construct(string $path)
    {
        parent::__construct('File "' . $path . '" does not exist.');
    }
}
