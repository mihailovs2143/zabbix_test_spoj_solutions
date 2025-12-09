<?php

declare(strict_types=1);

/**
 * TRT - Treats for the Cows
 * Interval Dynamic Programming - Maximize revenue by optimal treat selection
 *
 * @see https://www.spoj.com/problems/TRT/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class MaximumRevenueFromTreats
 *
 * Solves the treats revenue maximization problem using interval DP
 *
 * Algorithm:
 * - Treats are in a line, can only take from either end (left or right)
 * - Each treat is sold on day i with age multiplier i
 * - Price = treat_value × age
 * - dp[left][right] = max revenue for treats from index left to right
 * - Base case: dp[i][i] = value[i] × N (last treat sold on last day)
 * - Recurrence: dp[left][right] = max(
 *     value[left] × age + dp[left+1][right],  // take left
 *     value[right] × age + dp[left][right-1]  // take right
 *   )
 */
class MaximumRevenueFromTreats
{
    private array $treatValueArray;
    private int $daysCount;
    private array $dp;

    /**
     * @param array $treatValueArray Array of treat values
     * @throws InvalidArgumentException
     */
    public function __construct(array $treatValueArray)
    {
        $this->validateInput($treatValueArray);
        $this->treatValueArray = $treatValueArray;
        $this->daysCount = count($treatValueArray);
        $this->dp = [];
    }

    /**
     * Validate input constraints
     */
    private function validateInput(array $v): void
    {
        $count = count($v);
        if ($count < 1 || $count > 2000) {
            throw new InvalidArgumentException(
                "Number of treats must be between 1 and 2000, got: {$count}"
            );
        }

        foreach ($v as $value) {
            if (! is_numeric($value) || $value < 1 || $value > 1000) {
                throw new InvalidArgumentException(
                    "Treat values must be positive integers between 1 and 1000, got: {$value}"
                );
            }
        }
    }

    /**
     * Calculate maximum revenue using interval dynamic programming
     *
     * Strategy: Fill DP table diagonally, starting from base cases (single treats)
     * and building up to the full interval [0, N-1]
     *
     * @return int Maximum revenue
     */
    public function solve(): int
    {
        $n = $this->daysCount;

        // Initialize DP table: dp[left][right] = max revenue for interval [left, right]
        $this->dp = array_fill(0, $n, array_fill(0, $n, 0));

        // Base case: when only one treat remains (diagonal)
        // It will be sold on day N with age N
        for ($i = 0; $i < $n; $i++) {
            $this->dp[$i][$i] = $this->treatValueArray[$i] * $n;
        }

        // Fill DP table diagonally
        // len = length of interval being processed
        for ($len = 2; $len <= $n; $len++) {
            // Calculate age (day number) for this interval length
            // If interval has 'len' treats, we've already sold (n - len) treats
            // So current treat is sold on day (n - len + 1)
            $age = $n - ($len - 1);

            // left = starting position of interval
            for ($left = 0; $left <= $n - $len; $left++) {
                $right = $left + $len - 1;

                // Option 1: Take treat from left end
                $takeLeft = $this->treatValueArray[$left] * $age;
                if ($left + 1 <= $right) {
                    $takeLeft += $this->dp[$left + 1][$right];
                }

                // Option 2: Take treat from right end
                $takeRight = $this->treatValueArray[$right] * $age;
                if ($left <= $right - 1) {
                    $takeRight += $this->dp[$left][$right - 1];
                }

                // Choose the maximum
                $this->dp[$left][$right] = max($takeLeft, $takeRight);
            }
        }

        // Answer is the full interval [0, n-1]
        return $this->dp[0][$n - 1];
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
    $count = $reader->readInt();
    // Validate treats count according to problem constraints
    if ($count < 1 || $count > 2000) {
        fwrite(STDERR, "Error: Number of treats must be between 1 and 2000, got: $count\n");
        exit(1);
    }

    for ($i = 0; $i < $count; $i++) {
        $value = $reader->readInt();
        if ($value < 1 || $value > 1000) {
            fwrite(STDERR, "Error: Treat value must be between 1 and 1000, got: $value\n");
            exit(1);
        }
        $treatValueArray[$i] = $value;
    }

    try {
        $maxRevenue = new MaximumRevenueFromTreats($treatValueArray);
        echo $maxRevenue->solve() . PHP_EOL;
    } catch (InvalidArgumentException $e) {
        fwrite(STDERR, "Error in test case " . ($i + 1) . ": " . $e->getMessage() . "\n");
        exit(1);
    }
}
