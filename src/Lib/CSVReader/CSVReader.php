<?php

namespace Lib\CSVReader;

use Lib\CSVReader\Exception\MalformedCSVException;

class CSVReader implements \Iterator
{

    public static function createFromFilePath(
        string $filePath,
        string $delimiter = "\n",
        ?string $separator = ','
    ): CSVReader {
        $file = fopen($filePath, 'r');
        if ($file === false) {
            throw new MalformedCSVException(sprintf("Could not read file %s", $filePath));
        }

        return new self($file, $delimiter, $separator);
    }

    private $rowDelimiter;

    /* @var resource|null */
    private $fileHandle = null;

    private int $position = 0;

    /*  @var array<string, string> $data */
    private array $data = [];

    /*  @var array<string, string> $header */
    private array $header = [];

    /**
     * @param resource $file
     */
    public function __construct(
        private $file,
        private readonly string $delimiter = "\n",
        private readonly string $separator = ',',
        bool $hasHeader = false
    ) {
        if ($hasHeader) {
            $header = fgetcsv($this->file, 0, $this->separator);
            if ($header !== false) {
                $this->position++;
                $this->header = $header;
            } else {
                throw new \Exception("Missing Header");
            }
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->file) {
            fclose($this->file);
            $this->file = null;
        }
    }

    /**
     * Rewind iterator to the first element
     */
    public function rewind(): void
    {
        if ($this->file) {
            $this->position = 0;
            rewind($this->file);
        }
        $this->parseLine();
    }

    /**
     * Return the current row
     *
     * @return array<string, string>
     */
    public function current(): array
    {
        return $this->data;
    }

    /**
     * Return the key of the current row
     *
     * @return int
     */
    public function key(): mixed
    {
        return $this->position;
    }

    /**
     * Move forward to the next element
     */
    public function next(): void
    {
        $this->position++;
        $this->parseLine();
    }

    /**
     * Check if current position is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->data !== array();
    }

    /**
     * Parse each line to convert it to array
     *
     * @return void
     */
    private function parseLine()
    {
        $this->data = array();

        if (!feof($this->file)) {
            $line = trim(mb_convert_encoding(fgets($this->file), 'UTF-8', 'ISO-8859-1'));
            $this->data = str_getcsv($line, $this->separator);
        }
    }
}