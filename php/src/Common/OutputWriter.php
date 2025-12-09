<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Common;

/**
 * OutputWriter - Utility for writing output to STDOUT
 *
 * Provides convenient methods for writing different data types
 * to standard output, commonly used in competitive programming.
 */
class OutputWriter
{
    /** @var resource */
    private $handle;

    /**
     * Initialize output writer with STDOUT
     */
    public function __construct()
    {
        $this->handle = STDOUT;
    }

    /**
     * Write a line with newline
     *
     * @param string|int|float $output The output to write
     * @return void
     */
    public function writeLine(string|int|float $output): void
    {
        fwrite($this->handle, $output . PHP_EOL);
    }

    /**
     * Write without newline
     *
     * @param string|int|float $output The output to write
     * @return void
     */
    public function write(string|int|float $output): void
    {
        fwrite($this->handle, (string) $output);
    }

    /**
     * Write an array with space separator
     *
     * @param array $array The array to write
     * @param string $separator The separator (default: space)
     * @return void
     */
    public function writeArray(array $array, string $separator = ' '): void
    {
        fwrite($this->handle, implode($separator, $array) . PHP_EOL);
    }

    /**
     * Flush output buffer
     *
     * @return void
     */
    public function flush(): void
    {
        fflush($this->handle);
    }
}
