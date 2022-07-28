<?php

declare(strict_types=1);

namespace Jan\DevelopersTest;

use Jan\DevelopersTest\Configuration\OptionDefinition;
use Jan\DevelopersTest\Configuration\Options;
use Jan\DevelopersTest\Io\CsvFileWriter;
use Jan\DevelopersTest\Io\Exception\FileNotFoundException;
use Jan\DevelopersTest\Io\Exception\FileNotReadableException;
use Jan\DevelopersTest\Io\FileProcessor;
use Jan\DevelopersTest\Io\FileWriter;
use Jan\DevelopersTest\Transformation\DivisionTransformer;
use Jan\DevelopersTest\Transformation\LineTransformer;
use Jan\DevelopersTest\Transformation\MinusTransformer;
use Jan\DevelopersTest\Transformation\MultiplyTransformer;
use Jan\DevelopersTest\Transformation\PlusTransformer;

class Application
{
    public const EXIT_OK = 0;
    public const EXIT_ERROR = 1;

    public const OPTION_FILE = 'file';
    public const OPTION_ACTION = 'action';

    public const FILE_OUTPUT = 'result.csv';
    public const FILE_LOG = 'log.txt';

    public const LOG_MODE = FileWriter::MODE_OVERRIDE;

    /**
     * The options that can/have to be used as programm arguments
     * @var OptionDefinition[]
     */
    protected array $availableOptions;

    /**
     * The existing/processed options
     */
    protected ?Options $options = null;

    /**
     * Available file transformers to be used for output file
     * @var array<string, LineTransformer>
     */
    protected array $availableTransformers = [
        'plus' => PlusTransformer::class,
        'minus' => MinusTransformer::class,
        'multiply' => MultiplyTransformer::class,
        'division' => DivisionTransformer::class,
    ];

    public function __construct()
    {
        $this->availableOptions = [
            new OptionDefinition(OptionDefinition::TYPE_REQUIREDVALUE, self::OPTION_FILE, 'f'),
            new OptionDefinition(OptionDefinition::TYPE_REQUIREDVALUE, self::OPTION_ACTION, 'a'),
        ];
    }

    public function run(): int
    {
        $this->options = Options::getFromPhpOpts($this->availableOptions);

        try {
            $fileProcessor = new FileProcessor($this->options->getOptionValue(self::OPTION_FILE));
        } catch (FileNotFoundException | FileNotReadableException $e) {
            $this->error($e->getMessage());
            return self::EXIT_ERROR;
        }

        $transformer = $this->availableTransformers[$actionValue = $this->options->getOptionValue(self::OPTION_ACTION)] ?? null;

        if ($transformer === null) {
            $this->error('No transformer for action "' . $actionValue . '" found. Please choose one of the following: ' . implode(', ', array_keys($this->availableTransformers)) . '.');
            return self::EXIT_ERROR;
        }

        $fileProcessor->setLineTransformer(new $transformer());

        $outputWriter = new CsvFileWriter(self::FILE_OUTPUT);
        $logWriter = new FileWriter(self::FILE_LOG, self::LOG_MODE);

        try {
            $fileProcessor->process($outputWriter, $logWriter);
        } catch (Io\Exception\FileException $e) {
            $this->error($e->getMessage());
            try {
                $logWriter->writeLn($e->getMessage());
            } catch (Io\Exception\FileException) {
                //Nothing
            }
            $outputWriter->close();
            $logWriter->close();
            return self::EXIT_ERROR;
        }

        $outputWriter->close();
        $logWriter->close();

        return self::EXIT_OK;
    }

    protected function error(string $message): void
    {
        file_put_contents('php://stdout', $message);
    }
}
