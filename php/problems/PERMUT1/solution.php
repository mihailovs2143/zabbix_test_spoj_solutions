<?php

declare(strict_types=1);

/**
 * PERMUT1 - Permutations
 * Dynamic Programming - Count permutations with exactly k inversions
 *
 * @see https://www.spoj.com/problems/PERMUT1/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class PermutationCounter
 *
 * Counts the number of n-element permutations with exactly k inversions
 * using dynamic programming approach
 *
 * Algorithm:
 * - State: dp[i][j] = number of permutations of length i with exactly j inversions
 * - Base case: dp[1][0] = 1 (one element has 0 inversions)
 * - Transition: When adding element (i+1), we can insert it at any of (i+1) positions
 *   Inserting at position p creates p inversions (all elements to the right are smaller)
 *   dp[i+1][j] = sum of dp[i][j-p] for p = 0 to min(i, j)
 *
 * Example:
 * n=4, k=1 → Answer: 3
 * Permutations: [2,1,3,4], [1,3,2,4], [1,2,4,3]
 */
class PermutationCounter
{
    private int $n;
    private int $k;
    private array $dp;

    /**
     * @param int $n Length of permutation
     * @param int $k Number of inversions
     * @throws InvalidArgumentException
     */
    public function __construct(int $n, int $k)
    {
        $this->validateInput($n, $k);
        $this->n = $n;
        $this->k = $k;
        $this->dp = [];
    }

    /**
     * Validate input constraints
     */
    private function validateInput(int $n, int $k): void
    {
        if ($n < 1 || $n > 12) {
            throw new InvalidArgumentException(
                "n must be between 1 and 12, got: {$n}"
            );
        }

        $maxK = ($n * ($n - 1)) / 2;
        if ($k < 0 || $k > $maxK) {
            throw new InvalidArgumentException(
                "k must be between 0 and {$maxK} for n={$n}, got: {$k}"
            );
        }
    }

    /**
     * Count permutations with exactly k inversions using DP
     *
     * @return int Number of permutations
     */
    public function solve(): int
    {
        // Maximum possible inversions for length n is n*(n-1)/2
        $maxInversions = ($this->n * ($this->n - 1)) / 2;

        // If k exceeds maximum possible inversions, return 0
        if ($this->k > $maxInversions) {
            return 0;
        }

        // Initialize DP table
        // dp[i][j] = number of permutations of length i with j inversions
        $this->dp = array_fill(0, $this->n + 1, array_fill(0, $this->k + 1, 0));

        // Base case: permutation of length 1 has 0 inversions
        $this->dp[1][0] = 1;

        // Fill DP table
        for ($i = 1; $i < $this->n; $i++) {
            for ($j = 0; $j <= $this->k; $j++) {
                if ($this->dp[$i][$j] === 0) {
                    continue;
                }

                // When adding element (i+1), we can insert it at positions 0 to i
                // Inserting at position p creates p inversions
                for ($p = 0; $p <= $i && $j + $p <= $this->k; $p++) {
                    $this->dp[$i + 1][$j + $p] += $this->dp[$i][$j];
                }
            }
        }

        return $this->dp[$this->n][$this->k];
    }

    /**
     * Get the DP table (for debugging/testing)
     */
    public function getDpTable(): array
    {
        return $this->dp;
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $d = $reader->readInt();

    for ($i = 0; $i < $d; $i++) {
        $line = $reader->readLine();
        [$n, $k] = array_map('intval', explode(' ', $line));

        try {
            $counter = new PermutationCounter($n, $k);
            echo $counter->solve() . PHP_EOL;
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($i + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
