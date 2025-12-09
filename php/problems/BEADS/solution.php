<?php

declare(strict_types=1);

/**
 * BEADS - Glass Beads
 * Booth's Algorithm - Find minimum lexicographic rotation
 *
 * @see https://www.spoj.com/problems/BEADS/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class GlassBeads
 *
 * Solves the glass beads problem using Booth's Algorithm
 * Finds the starting position of the lexicographically minimal rotation
 */
class GlassBeads
{
    private string $beads;
    private int $declaredLength;

    /**
     * @param string $beads String of beads (circular)
     * @param int|null $declaredLength Optional declared length for validation
     */
    public function __construct(string $beads, ?int $declaredLength = null)
    {
        $this->declaredLength = $declaredLength ?? strlen($beads);
        $this->validateInput($beads);
        $this->beads = $beads;
    }

    /**
     * Validate input according to problem constraints
     *
     * @throws InvalidArgumentException if constraints are violated
     */
    private function validateInput(string $beads): void
    {
        $length = strlen($beads);

        if ($length < 1 || $length > 10_000_000) {
            throw new InvalidArgumentException(
                "String length must be between 1 and 10000000, got: $length"
            );
        }

        if ($length !== $this->declaredLength) {
            throw new InvalidArgumentException(
                "Declared length ({$this->declaredLength}) does not match actual string length ({$length})"
            );
        }

        if (! preg_match('/^[a-z]+$/', $beads)) {
            throw new InvalidArgumentException(
                "String must contain only lowercase letters (a-z)"
            );
        }
    }

    /**
     * Find the starting position of the lexicographically minimal rotation
     *
     * Algorithm: Booth's Algorithm
     * 1. Concatenate string with itself (s + s)
     * 2. Use failure function to skip comparisons
     * 3. Find minimal rotation in O(n) time
     *
     * @return int Starting position (1-based)
     */
    public function solve(): int
    {
        $n = strlen($this->beads);
        $s = $this->beads . $this->beads; // Concatenate string with itself

        // Failure function array
        $f = array_fill(0, strlen($s), -1);
        $k = 0; // Current candidate for minimal rotation

        for ($j = 1; $j < strlen($s); $j++) {
            $sj = $s[$j];
            $i = $f[$j - $k - 1];

            // Compare characters and update failure function
            while ($i !== -1 && $sj !== $s[$k + $i + 1]) {
                if ($sj < $s[$k + $i + 1]) {
                    $k = $j - $i - 1;
                }
                $i = $f[$i];
            }

            // Handle mismatch at the start of the pattern
            if ($i === -1 && $sj !== $s[$k]) {
                if ($sj < $s[$k]) {
                    $k = $j;
                }
                $f[$j - $k] = -1;
            } else {
                $f[$j - $k] = $i + 1;
            }

            // Early termination if we've processed enough
            if ($j - $k + 1 === $n) {
                break;
            }
        }

        return $k + 1; // Return 1-based position
    }

    /**
     * Modified Booth's Algorithm with position skipping
     * Optimized version that skips obviously bad positions
     * Time complexity: O(n log n) average, O(n²) worst case
     *
     * Key insight: If rotation at position i loses to rotation at j
     * at character k, then all rotations from i+1 to i+k also lose.
     *
     * @return int Starting position (1-based)
     */
    public function solveModified(): int
    {
        $n = strlen($this->beads);
        $s = $this->beads . $this->beads; // Double the string

        $minPos = 0; // Current best rotation position

        for ($testPos = 1; $testPos < $n; $testPos++) {
            $k = 0; // Length of matching prefix

            // Compare minPos and testPos rotations
            while ($k < $n && $s[$minPos + $k] === $s[$testPos + $k]) {
                $k++;
            }

            if ($k >= $n) {
                // All characters match, identical rotation
                continue;
            }

            // Check which rotation is lexicographically smaller
            if ($s[$minPos + $k] > $s[$testPos + $k]) {
                // testPos is better than minPos
                // Skip all positions from minPos to minPos+k (they're all worse)
                $minPos = $testPos;

                // We could also skip positions testPos+1 to testPos+k here,
                // but the outer loop will handle them anyway
            }
            // If minPos is better, continue checking next positions
        }

        return $minPos + 1; // Return 1-based position
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    for ($t = 0; $t < $testCases; $t++) {
        // Read declared string length for validation
        $declaredLength = $reader->readInt();

        // Read the bead string
        $beads = trim($reader->readLine());

        try {
            $solver = new GlassBeads($beads, $declaredLength);
            echo $solver->solveModified() . "\n";
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
