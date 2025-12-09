<?php

declare(strict_types=1);

/**
 * POUR1 - Pouring Water
 * BFS Solution - Shortest path in state space
 *
 * @see https://www.spoj.com/problems/POUR1/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class WaterJugSolver
 *
 * Solves the water jug problem using BFS to find minimum operations
 */
class WaterJugSolver
{
    private int $capacityA;
    private int $capacityB;
    private int $target;

    /**
     * @param int $capacityA Capacity of jug A in liters
     * @param int $capacityB Capacity of jug B in liters
     * @param int $target Target volume to measure
     * @throws InvalidArgumentException if input violates constraints
     */
    public function __construct(int $capacityA, int $capacityB, int $target)
    {
        $this->validateInput($capacityA, $capacityB, $target);

        $this->capacityA = $capacityA;
        $this->capacityB = $capacityB;
        $this->target = $target;
    }

    /**
     * Validate input according to problem constraints
     *
     * @param int $a Capacity of jug A
     * @param int $b Capacity of jug B
     * @param int $c Target volume
     * @throws InvalidArgumentException if constraints are violated
     */
    private function validateInput(int $a, int $b, int $c): void
    {
        if ($a <= 0 || $a > 40000) {
            throw new InvalidArgumentException(
                "Capacity A must be positive and not larger than 40000, got: $a"
            );
        }

        if ($b <= 0 || $b > 40000) {
            throw new InvalidArgumentException(
                "Capacity B must be positive and not larger than 40000, got: $b"
            );
        }

        if ($c < 0 || $c > 40000) {
            throw new InvalidArgumentException(
                "Target must be non-negative and not larger than 40000, got: $c"
            );
        }
    }

    /**
     * Find minimum operations to measure target volume
     *
     * @return int Minimum operations, or -1 if impossible
     */
    public function solve(): int
    {
        // Quick checks
        if ($this->target === 0) {
            return 0;
        }

        if ($this->target === $this->capacityA || $this->target === $this->capacityB) {
            return 1;
        }

        if ($this->target > max($this->capacityA, $this->capacityB)) {
            return -1;
        }

        // Mathematical impossibility check using Bézout's identity
        // We can measure C liters iff C is divisible by gcd(A, B)
        if ($this->target % $this->gcd($this->capacityA, $this->capacityB) !== 0) {
            return -1;
        }

        return $this->bfs();
    }

    /**
     * Breadth-First Search to find shortest path
     *
     * @return int Minimum operations, or -1 if impossible
     */
    private function bfs(): int
    {
        $queue = new SplQueue();
        $visited = [];

        // Initial state: (jugA, jugB, steps)
        $queue->enqueue([0, 0, 0]);
        $visited['0,0'] = true;

        while (! $queue->isEmpty()) {
            [$jugA, $jugB, $steps] = $queue->dequeue();

            // Check if we reached the target
            if ($jugA === $this->target || $jugB === $this->target) {
                return $steps;
            }

            // Generate all possible next states
            $nextStates = $this->getNextStates($jugA, $jugB, $steps);

            foreach ($nextStates as [$newA, $newB, $newSteps]) {
                $stateKey = "$newA,$newB";

                if (! isset($visited[$stateKey])) {
                    $visited[$stateKey] = true;
                    $queue->enqueue([$newA, $newB, $newSteps]);
                }
            }
        }

        return -1; // Target not reachable
    }

    /**
     * Generate all possible next states from current state
     *
     * @param int $jugA Current amount in jug A
     * @param int $jugB Current amount in jug B
     * @param int $steps Current step count
     * @return array[] Array of [newA, newB, newSteps]
     */
    private function getNextStates(int $jugA, int $jugB, int $steps): array
    {
        $states = [];
        $nextSteps = $steps + 1;

        // Operation 1: Fill jug A
        if ($jugA < $this->capacityA) {
            $states[] = [$this->capacityA, $jugB, $nextSteps];
        }

        // Operation 2: Fill jug B
        if ($jugB < $this->capacityB) {
            $states[] = [$jugA, $this->capacityB, $nextSteps];
        }

        // Operation 3: Empty jug A
        if ($jugA > 0) {
            $states[] = [0, $jugB, $nextSteps];
        }

        // Operation 4: Empty jug B
        if ($jugB > 0) {
            $states[] = [$jugA, 0, $nextSteps];
        }

        // Operation 5: Pour from A to B
        if ($jugA > 0 && $jugB < $this->capacityB) {
            $pourAmount = min($jugA, $this->capacityB - $jugB);
            $states[] = [$jugA - $pourAmount, $jugB + $pourAmount, $nextSteps];
        }

        // Operation 6: Pour from B to A
        if ($jugB > 0 && $jugA < $this->capacityA) {
            $pourAmount = min($jugB, $this->capacityA - $jugA);
            $states[] = [$jugA + $pourAmount, $jugB - $pourAmount, $nextSteps];
        }

        return $states;
    }

    /**
     * Calculate Greatest Common Divisor using Euclidean algorithm
     *
     * @param int $a First number
     * @param int $b Second number
     * @return int GCD of a and b
     */
    private function gcd(int $a, int $b): int
    {
        while ($b !== 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }

        return $a;
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    // Validate test cases count according to problem constraints
    if ($testCases < 1 || $testCases > 100) {
        fwrite(STDERR, "Error: Number of test cases must be between 1 and 100, got: $testCases\n");
        exit(1);
    }

    for ($t = 0; $t < $testCases; $t++) {
        // SPOJ format: each value on separate line (a, b, c)
        $a = $reader->readInt();
        $b = $reader->readInt();
        $c = $reader->readInt();

        try {
            $solver = new WaterJugSolver($a, $b, $c);
            echo $solver->solve() . PHP_EOL;
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
