<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Io;

use Jan\DevelopersTest\Io\Exception\FileException;
use Jan\DevelopersTest\Io\Exception\FileNotFoundException;
use Jan\DevelopersTest\Io\Exception\FileNotReadableException;
use Jan\DevelopersTest\Transformation\Exception\InvalidRowException;
use Jan\DevelopersTest\Transformation\LineTransformer;

/**
 * FileProcessor instance.
 * This class reads the input file, process it with the optional line transformer and writes the result to our new csv file
 */
class FileProcessor
{
    protected string $path;

    protected ?LineTransformer $lineTransformer = null;

    /**
     * @param string $path
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }
        if (!is_readable($path)) {
            throw new FileNotReadableException($path);
        }
        $this->path = $path;
    }

    public function setLineTransformer(LineTransformer $lineTransformer): void
    {
        $this->lineTransformer = $lineTransformer;
    }

    /**
     * @throws FileException
     */
    public function process(CsvFileWriter $outputWriter, FileWriter $logWriter): void
    {
        if ($this->lineTransformer === null) {
            $logWriter->writeLn('Start csv processing w/o transformer');
        } else {
            $logWriter->writeLn('Starting csv processing with ' . $this->lineTransformer::class);
        }
        $handle = fopen($this->path, 'r');
        if ($handle === false) {
            throw new FileException('Failed to open file "' . $this->path . '" with mode "r".');
        }

        if (fgets($handle, 4) !== "\xef\xbb\xbf") {
            rewind($handle);
        }

        if (feof($handle)) {
            fclose($handle);
            throw new FileException('File "' . $this->path . '" has no content.');
        }

        $lineNumber = 0;
        while (($line = fgetcsv($handle, null, ';')) !== false) {
            $lineNumber++;
            if ($this->lineTransformer !== null) {
                try {
                    $line = $this->lineTransformer->transform($line);
                } catch (InvalidRowException $e) {
                    $logWriter->writeLn(sprintf('Line %d: %s', $lineNumber, $e->getMessage()));
                    continue;
                }
            }
            if ($line !== null) {
                $outputWriter->writeCsvRow($line);
            }
        }

        fclose($handle);

        if ($lineNumber === 0) {
            throw new FileException('File "' . $this->path . '" has no content.');
        }

        $logWriter->writeLn('Processing has finished');
    }
}
