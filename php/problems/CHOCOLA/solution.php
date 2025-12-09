<?php

declare(strict_types=1);

/**
 * CHOCOLA - Chocolate
 * Greedy Algorithm - Break chocolate with minimum cost
 *
 * @see https://www.spoj.com/problems/CHOCOLA/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class ChocolateBreaker
 *
 * Solves the chocolate breaking problem using greedy approach
 * Strategy: Break along most expensive lines first (when fewer pieces exist)
 */
class ChocolateBreaker
{
    private int $rows;
    private int $cols;
    private array $verticalCosts;
    private array $horizontalCosts;

    /**
     * @param int $rows Number of rows (m)
     * @param int $cols Number of columns (n)
     * @param array $verticalCosts Costs for vertical cuts [x1, x2, ..., x(m-1)]
     * @param array $horizontalCosts Costs for horizontal cuts [y1, y2, ..., y(n-1)]
     */
    public function __construct(int $rows, int $cols, array $verticalCosts, array $horizontalCosts)
    {
        $this->validateInput($rows, $cols, $verticalCosts, $horizontalCosts);

        $this->rows = $rows;
        $this->cols = $cols;
        $this->verticalCosts = $verticalCosts;
        $this->horizontalCosts = $horizontalCosts;
    }

    /**
     * Validate input according to problem constraints
     *
     * @throws InvalidArgumentException if constraints are violated
     */
    private function validateInput(int $rows, int $cols, array $verticalCosts, array $horizontalCosts): void
    {
        if ($rows < 2 || $rows > 1000) {
            throw new InvalidArgumentException(
                "Number of rows must be between 2 and 1000, got: $rows"
            );
        }

        if ($cols < 2 || $cols > 1000) {
            throw new InvalidArgumentException(
                "Number of columns must be between 2 and 1000, got: $cols"
            );
        }

        if (count($verticalCosts) !== $rows - 1) {
            throw new InvalidArgumentException(
                "Expected " . ($rows - 1) . " vertical costs, got: " . count($verticalCosts)
            );
        }

        if (count($horizontalCosts) !== $cols - 1) {
            throw new InvalidArgumentException(
                "Expected " . ($cols - 1) . " horizontal costs, got: " . count($horizontalCosts)
            );
        }

        foreach ($verticalCosts as $cost) {
            if ($cost < 1 || $cost > 1000) {
                throw new InvalidArgumentException(
                    "Vertical cost must be between 1 and 1000, got: $cost"
                );
            }
        }

        foreach ($horizontalCosts as $cost) {
            if ($cost < 1 || $cost > 1000) {
                throw new InvalidArgumentException(
                    "Horizontal cost must be between 1 and 1000, got: $cost"
                );
            }
        }
    }

    /**
     * Calculate minimum cost to break chocolate into 1x1 pieces
     *
     * Algorithm:
     * 1. Sort all cuts by cost (descending)
     * 2. Always choose the most expensive cut available
     * 3. Multiply cost by number of existing pieces in perpendicular direction
     *
     * @return int Minimum total cost
     */
    public function solve(): int
    {
        // Sort cuts in descending order (most expensive first)
        $vertical = $this->verticalCosts;
        $horizontal = $this->horizontalCosts;

        rsort($vertical);
        rsort($horizontal);

        $resultTotalCost = 0;
        $horizontalPieces = 1; // Number of horizontal pieces at start
        $verticalPieces = 1;   // Number of vertical pieces at start

        $vIndex = 0; // Index for vertical cuts
        $hIndex = 0; // Index for horizontal cuts

        // Process all cuts greedily
        while ($vIndex < count($vertical) && $hIndex < count($horizontal)) {
            if ($vertical[$vIndex] >= $horizontal[$hIndex]) {
                // Make vertical cut (splits horizontally)
                $resultTotalCost += $vertical[$vIndex] * $horizontalPieces;
                $verticalPieces++;
                $vIndex++;
            } else {
                // Make horizontal cut (splits vertically)
                $resultTotalCost += $horizontal[$hIndex] * $verticalPieces;
                $horizontalPieces++;
                $hIndex++;
            }
        }

        // Process remaining vertical cuts
        while ($vIndex < count($vertical)) {
            $resultTotalCost += $vertical[$vIndex] * $horizontalPieces;
            $verticalPieces++;
            $vIndex++;
        }

        // Process remaining horizontal cuts
        while ($hIndex < count($horizontal)) {
            $resultTotalCost += $horizontal[$hIndex] * $verticalPieces;
            $horizontalPieces++;
            $hIndex++;
        }

        return $resultTotalCost;
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    // Validate test cases count
    if ($testCases < 1 || $testCases > 20) {
        fwrite(STDERR, "Error: Number of test cases must be between 1 and 20, got: $testCases\n");
        exit(1);
    }

    for ($t = 0; $t < $testCases; $t++) {
        // Read blank line before each test case
        $reader->readLine();

        // Read dimensions
        [$m, $n] = $reader->readIntArray();

        // Read vertical costs (m-1 values)
        $verticalCosts = [];
        for ($i = 0; $i < $m - 1; $i++) {
            $verticalCosts[] = $reader->readInt();
        }

        // Read horizontal costs (n-1 values)
        $horizontalCosts = [];
        for ($i = 0; $i < $n - 1; $i++) {
            $horizontalCosts[] = $reader->readInt();
        }

        try {
            $solver = new ChocolateBreaker($m, $n, $verticalCosts, $horizontalCosts);
            echo $solver->solve() . "\n";
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
