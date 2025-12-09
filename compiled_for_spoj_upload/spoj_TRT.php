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

        foreach($v as $value) {
            if (!is_numeric($value) || $value < 1 || $value > 1000) {
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

if (php_sapi_name() === 'cli' && !defined('SPOJ_CLI_MODE') && !defined('PHPUNIT_COMPOSER_INSTALL')) {
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
