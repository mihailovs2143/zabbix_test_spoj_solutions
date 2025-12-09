<?php

declare(strict_types=1);

class InputReader
{
    /** @var resource */
    private $handle;

    /**
     * Initialize input reader with STDIN
     */
    public function __construct()
    {
        $this->handle = STDIN;
    }

    /**
     * Read a single line and trim whitespace
     *
     * @return string The trimmed line
     */
    public function readLine(): string
    {
        $line = fgets($this->handle);

        return $line !== false ? trim($line) : '';
    }

    /**
     * Read a single integer
     *
     * @return int The integer value
     */
    public function readInt(): int
    {
        return (int) $this->readLine();
    }

    /**
     * Read a single float/double
     *
     * @return float The float value
     */
    public function readFloat(): float
    {
        return (float) $this->readLine();
    }

    /**
     * Read a line and split into array of integers
     *
     * @return int[] Array of integers
     */
    public function readIntArray(): array
    {
        $line = $this->readLine();
        if ($line === '') {
            return [];
        }

        return array_map('intval', explode(' ', $line));
    }

    /**
     * Read a line and split into array of floats
     *
     * @return float[] Array of floats
     */
    public function readFloatArray(): array
    {
        $line = $this->readLine();
        if ($line === '') {
            return [];
        }

        return array_map('floatval', explode(' ', $line));
    }

    /**
     * Read a line and split into array of strings
     *
     * @return string[] Array of strings
     */
    public function readStringArray(): array
    {
        $line = $this->readLine();
        if ($line === '') {
            return [];
        }

        return explode(' ', $line);
    }

    /**
     * Read entire remaining input as string
     *
     * @return string All remaining input
     */
    public function readAll(): string
    {
        return stream_get_contents($this->handle);
    }

    /**
     * Check if there is more input to read
     *
     * @return bool True if more input available
     */
    public function hasMoreInput(): bool
    {
        return !feof($this->handle);
    }
}

class AggressiveCows
{
    private int $numberOfStalls;
    private int $cowsCount;
    private array $stallPositions;

    /**
     * @param int $numberOfStalls Number of stalls
     * @param int $cowsCount Number of cows
     * @param array $stallPositions ALLOWED Positions of stalls
     */
    public function __construct(int $numberOfStalls, int $cowsCount, array $stallPositions)
    {
        $this->validateInput($numberOfStalls, $cowsCount, $stallPositions);
        
        $this->numberOfStalls = $numberOfStalls;
        $this->cowsCount = $cowsCount;
        $this->stallPositions = $stallPositions;
    }

    /**
     * Validate input according to problem constraints
     * 
     * @throws InvalidArgumentException if constraints are violated
     */
    private function validateInput(int $numberOfStalls, int $cowsCount, array $stallPositions): void
    {
        if ($numberOfStalls < 2 || $numberOfStalls > 100_000) {
            throw new InvalidArgumentException(
                "Number of stalls must be between 2 and 100000, got: $numberOfStalls"
            );
        }

        if ($cowsCount < 2 || $cowsCount > $numberOfStalls) {
            throw new InvalidArgumentException(
                "Number of cows must be between 2 and $numberOfStalls (number of stalls), got: $cowsCount"
            );
        }

        if (count($stallPositions) !== $numberOfStalls) {
            throw new InvalidArgumentException(
                "Expected " . ($numberOfStalls) . " stall positions, got: " . count($stallPositions)
            );
        }


        foreach ($stallPositions as $position) {
            if ($position < 0 || $position > 1_000_000_000) {
                throw new InvalidArgumentException(
                    "$position is out of allowed stall position range (0 to 1`000`000`000)"
                );
            }
        }
    }

    /**
     * Calculate maximize minimum distance to place cows in stalls
     * 
     * Algorithm:
     * 1. Sort stall positions
     * 2. Use binary search on distance:
     *   - For each mid distance, check if cows can be placed
     *  - Adjust search range based on feasibility
     * @return int Maximum minimum distance
     */
    public function solve(): int
    {
        // Sort cuts in descending order (most expensive first)
        $positions = $this->stallPositions;
        $n=$this->numberOfStalls;
        $c=$this->cowsCount;

        sort($positions);
        $left = 1; // Minimum possible distance
        $right = $positions[$n - 1] - $positions[0]; // Maximum possible distance
        $answer= 0;
        
        while ($left <= $right) {
            $mid = intdiv($left + $right, 2);
            if ($this->canPlaceCows($positions, $n, $c, $mid)) {
                $answer = $mid; // Update answer
                $left = $mid + 1; // Try for a larger distance
            } else {
                $right = $mid - 1; // Try for a smaller distance
            }
        }
        return $answer;
    
    }
    private function canPlaceCows(array $positions, int $n, int $c, int $distance): bool
    {
        $count = 1; // Place first cow in the first stall
        $lastPosition = $positions[0];
        
        for ($i = 1; $i < $n; $i++) {
            if ($positions[$i] - $lastPosition >= $distance) {
                $count++;
                $lastPosition = $positions[$i];
                if ($count >= $c) {
                    return true; // Successfully placed all cows
                }
            }
        }
        return false; // Not enough cows could be placed
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && !defined('SPOJ_CLI_MODE') && !defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    // Validate test cases count according to problem constraints
    // if ($testCases < 1 || $testCases > 20) {
    //     fwrite(STDERR, "Error: Number of test cases must be between 1 and 20, got: $testCases\n");
    //     exit(1);
    // }

    for ($t = 0; $t < $testCases; $t++) {
                
        // Read Stalls and Cows
        [$n, $c] = $reader->readIntArray();
        
        // Pre-validate before reading all positions
        if ($n < 2 || $n > 100_000) {
            fwrite(STDERR, "Error: Number of stalls must be between 2 and 100000, got: $n\n");
            exit(1);
        }
        if ($c < 2 || $c > $n) {
            fwrite(STDERR, "Error: Number of cows must be between 2 and $n, got: $c\n");
            exit(1);
        }
        
        // Read stall positions (n times)
        $stallPositions = [];
        for ($i = 0; $i < $n; $i++) {
            $stallPositions[] = $reader->readInt();
        }
        
        // Validate that we read exactly n positions
        if (count($stallPositions) !== $n) {
            fwrite(STDERR, "Error: Expected $n stall positions, got: " . count($stallPositions) . "\n");
            exit(1);
        }

        try {
            $solver = new AggressiveCows( $n,$c, $stallPositions);
            echo $solver->solve() . "\n";
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
