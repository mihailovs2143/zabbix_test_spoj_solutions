<?php

declare(strict_types=1);

/**
 * CMPLS - Complete the Sequence!
 * Finite Differences Algorithm - Find polynomial of lowest degree and complete sequence
 *
 * @see https://www.spoj.com/problems/CMPLS/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class SequenceCompleter
 *
 * Solves the sequence completion problem using finite differences method
 * Finds the polynomial of minimum degree that describes the sequence
 */
class SequenceCompleter
{
    private int $sequenceLength;
    private int $countToComplete;
    private array $sequence;
    private array $differencesTable;

    /**
     * @param int $sequenceLength Length of given sequence (S)
     * @param int $countToComplete Number of values to complete (C)
     * @param array $sequence Initial sequence values
     */
    public function __construct(int $sequenceLength, int $countToComplete, array $sequence)
    {
        $this->validateInput($sequenceLength, $countToComplete, $sequence);

        $this->sequenceLength = $sequenceLength;
        $this->countToComplete = $countToComplete;
        $this->sequence = $sequence;
        $this->differencesTable = [];
    }

    /**
     * Validate input according to problem constraints
     *
     * @throws InvalidArgumentException if constraints are violated
     */
    private function validateInput(int $sequenceLength, int $countToComplete, array $sequence): void
    {
        if ($sequenceLength < 1 || $sequenceLength >= 100) {
            throw new InvalidArgumentException(
                "Sequence length must be between 1 and 99, got: $sequenceLength"
            );
        }

        if ($countToComplete < 1 || $countToComplete >= 100) {
            throw new InvalidArgumentException(
                "Count to complete must be between 1 and 99, got: $countToComplete"
            );
        }

        if (($sequenceLength + $countToComplete) > 100) {
            throw new InvalidArgumentException(
                "Sum of S and C must not exceed 100, got: " . ($sequenceLength + $countToComplete)
            );
        }

        if (count($sequence) !== $sequenceLength) {
            throw new InvalidArgumentException(
                "Expected $sequenceLength sequence values, got: " . count($sequence)
            );
        }
    }

    /**
     * Complete the sequence using finite differences method
     *
     * Algorithm:
     * 1. Build differences table by computing consecutive differences
     * 2. Continue until we get a constant row (all values same)
     * 3. Extend the constant row by needed count
     * 4. Reconstruct values bottom-up to complete original sequence
     *
     * @return array Completed values
     */
    public function solve(): array
    {
        // Step 1: Build differences table (going down)
        $this->buildDifferencesTable();

        // Step 2: Extend the constant row
        $this->extendConstantRow();

        // Step 3: Reconstruct sequence (going up)
        $this->reconstructSequence();

        // Step 4: Extract the completed values
        return $this->getCompletedValues();
    }

    /**
     * Build the differences table by computing consecutive differences
     * until we reach a constant row
     */
    private function buildDifferencesTable(): void
    {
        // First row is the original sequence
        $this->differencesTable[0] = $this->sequence;

        $level = 0;

        // Keep computing differences until we get a constant row
        while (! $this->isConstantRow($this->differencesTable[$level])) {
            $this->differencesTable[$level + 1] = [];
            $currentRow = $this->differencesTable[$level];

            // Compute differences: row[i] = prevRow[i+1] - prevRow[i]
            for ($i = 1; $i < count($currentRow); $i++) {
                $this->differencesTable[$level + 1][] = $currentRow[$i] - $currentRow[$i - 1];
            }

            $level++;
        }
    }

    /**
     * Check if a row contains all the same values (constant)
     *
     * @param array $row Row to check
     * @return bool True if all values are the same
     */
    private function isConstantRow(array $row): bool
    {
        if (empty($row)) {
            return true;
        }

        $firstValue = $row[0];
        foreach ($row as $value) {
            if ($value !== $firstValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extend the constant row (bottom row) by adding needed values
     */
    private function extendConstantRow(): void
    {
        $lastLevel = count($this->differencesTable) - 1;
        $constantValue = $this->differencesTable[$lastLevel][0];

        // Add countToComplete + 1 more values to ensure we can reconstruct
        for ($i = 0; $i <= $this->countToComplete; $i++) {
            $this->differencesTable[$lastLevel][] = $constantValue;
        }
    }

    /**
     * Reconstruct the sequence by going up from constant row
     * Each value is computed as: value[i] = value[i-1] + lowerRow[i-1]
     */
    private function reconstructSequence(): void
    {
        $lastLevel = count($this->differencesTable) - 1;

        // Start from second-to-last level and go up
        for ($level = $lastLevel - 1; $level >= 0; $level--) {
            $currentRowSize = count($this->differencesTable[$level]);
            $lowerRow = $this->differencesTable[$level + 1];

            // Extend current row using lower row values
            for ($i = 0; $i < $this->countToComplete; $i++) {
                $lastValue = $this->differencesTable[$level][$currentRowSize + $i - 1];
                $diff = $lowerRow[$currentRowSize + $i - 1];
                $this->differencesTable[$level][] = $lastValue + $diff;
            }
        }
    }

    /**
     * Extract the completed values from the reconstructed first row
     *
     * @return array Array of completed values
     */
    private function getCompletedValues(): array
    {
        $result = [];
        $firstRow = $this->differencesTable[0];

        for ($i = $this->sequenceLength; $i < $this->sequenceLength + $this->countToComplete; $i++) {
            $result[] = $firstRow[$i];
        }

        return $result;
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    for ($t = 0; $t < $testCases; $t++) {
        // Read S and C
        [$s, $c] = $reader->readIntArray();

        // Read sequence values
        $sequence = $reader->readIntArray();

        try {
            $solver = new SequenceCompleter($s, $c, $sequence);
            $result = $solver->solve();

            echo implode(' ', $result) . "\n";
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
