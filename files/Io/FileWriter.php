<?php

declare(strict_types=1);

namespace Jan\DevelopersTest\Io;

use Jan\DevelopersTest\Io\Exception\FileException;
use Jan\DevelopersTest\Io\Exception\FileNotWriteableException;

/**
 * Basic class for writing files
 */
class FileWriter
{
    public const MODE_OVERRIDE = 'w';
    public const MODE_APPEND = 'a+';

    protected string $path;

    /** @var resource|null */
    protected $handle;

    protected string $mode;

    /**
     * @param string $path
     * @throws FileNotWriteableException
     */
    public function __construct(string $path, string $mode = self::MODE_OVERRIDE)
    {
        if (file_exists($path) && !is_writable($path)) {
            throw new FileNotWriteableException($path);
        }
        assert($mode === self::MODE_OVERRIDE || $mode === self::MODE_APPEND, '$mode must correspond to one of the MODE_* constants');
        $this->path = $path;
        $this->mode = $mode;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @throws FileException
     */
    protected function open(): void
    {
        $handle = fopen($this->path, $this->mode);
        if ($handle === false) {
            throw new FileException('Failed to open file "' . $this->path . '" with mode "' . $this->mode . '".');
        }
        $this->handle = $handle;
    }

    /**
     * @throws FileException
     */
    public function writeLn(string $row): void
    {
        if ($this->handle === null) {
            $this->open();
        }
        fwrite($this->handle, str_replace(["\n", "\r"], ['\\n', '\\r'], $row) . "\r\n");
    }

    public function close(): void
    {
        if ($this->handle !== null) {
            fclose($this->handle);
            clearstatcache(false, $this->path);
        }
        $this->handle = null;
    }
}
