<?php

declare(strict_types=1);

/**
 * ARITH - Simple Arithmetics
 * String-based arithmetic with formatted output
 *
 * @see https://www.spoj.com/problems/ARITH/
 */

require_once __DIR__ . '/../../src/Common/InputReader.php';

use ZabbixSPOJ\Common\InputReader;

/**
 * Class ArithmeticFormatter
 *
 * Performs arithmetic operations on very large numbers (up to 500 digits)
 * and formats output according to SPOJ requirements
 */
class ArithmeticFormatter
{
    private string $num1;
    private string $num2;
    private string $operation;
    /**
     * @param string $num1 First number as a string
     * @param string $operation Arithmetic operation ('+', '-', '*')
     * @param string $num2 Second number as a string
     * @throws InvalidArgumentException if input violates constraints
     */
    public function __construct(string $num1, string $operation, string $num2)
    {
        $this->validateInput($num1, $num2, $operation);
        $this->num1 = $num1;
        $this->operation = $operation;
        $this->num2 = $num2;
    }


    /**
     * Validate input numbers according to problem constraints
     *
     * @param string $a First number
     * @param string $b Second number
     * @throws InvalidArgumentException if constraints are violated
     */
    private function validateInput(string $a, string $b, string $operation): void
    {
        //check if numbers are positive integers with up to 500 digits and no leading zeros (except for zero itself)
        if (! preg_match('/^(0|[1-9]\d{0,499})$/', $a)) {
            throw new InvalidArgumentException(
                "First number must be a positive integer with up to 500 digits, got: $a"
            );
        }
        if (! preg_match('/^(0|[1-9]\d{0,499})$/', $b)) {
            throw new InvalidArgumentException(
                "Second number must be a positive integer with up to 500 digits, got: $b"
            );
        }
        if ($operation == '-') {
            // Compare as numbers: a must be >= b
            if (strlen($a) < strlen($b) || (strlen($a) === strlen($b) && $a < $b)) {
                throw new InvalidArgumentException(
                    "For subtraction, the first number must be greater than or equal to the second number, got: $a - $b"
                );
            }
        }
    }

    /**
     * Solve and format the arithmetic operation
     */
    public function solve(): string
    {
        return match ($this->operation) {
            '+' => $this->formatAddition(),
            '-' => $this->formatSubtraction(),
            '*' => $this->formatMultiplication(),
            default => throw new InvalidArgumentException("Invalid operation: {$this->operation}")
        };
    }

    /**
     * Format addition output
     */
    private function formatAddition(): string
    {
        $result = $this->add($this->num1, $this->num2);
        $maxLen = max(strlen($this->num1), strlen($this->num2) + 1, strlen($result));

        $lines = [];
        $lines[] = str_pad($this->num1, $maxLen, ' ', STR_PAD_LEFT);
        $lines[] = str_pad($this->operation . $this->num2, $maxLen, ' ', STR_PAD_LEFT);
        $lines[] = str_repeat('-', $maxLen);
        $lines[] = str_pad($result, $maxLen, ' ', STR_PAD_LEFT);

        return implode(PHP_EOL, $lines);
    }

    /**
     * Format subtraction output
     */
    private function formatSubtraction(): string
    {
        $result = $this->subtract($this->num1, $this->num2);
        $maxLen = max(strlen($this->num1), strlen($this->num2) + 1, strlen($result));

        $lines = [];
        $lines[] = str_pad($this->num1, $maxLen, ' ', STR_PAD_LEFT);
        $lines[] = str_pad($this->operation . $this->num2, $maxLen, ' ', STR_PAD_LEFT);
        $lines[] = str_repeat('-', $maxLen);
        $lines[] = str_pad($result, $maxLen, ' ', STR_PAD_LEFT);

        return implode(PHP_EOL, $lines);
    }

    /**
     * Format multiplication output
     */
    private function formatMultiplication(): string
    {
        $multiplyResult = $this->multiplyGetPartials($this->num1, $this->num2);
        $partials = $multiplyResult['partials'];
        $finalResult = $multiplyResult['result'];

        // Calculate max length - rightmost position
        $maxLen = strlen($finalResult);

        $lines = [];

        // Line 1: First number (right-aligned to final result)
        $lines[] = str_pad($this->num1, $maxLen, ' ', STR_PAD_LEFT);

        // Line 2: Operator + second number
        $line2 = $this->operation . $this->num2;
        $lines[] = str_pad($line2, $maxLen, ' ', STR_PAD_LEFT);

        // Line 3: First dashes (covers operator line or first partial)
        $firstPartialLen = strlen($partials[0]['value']);
        $dashLen1 = max(strlen($line2), $firstPartialLen);
        $lines[] = str_pad(str_repeat('-', $dashLen1), $maxLen, ' ', STR_PAD_LEFT);

        // Partial products - каждый выравнивается по своей позиции справа
        foreach ($partials as $data) {
            $partial = $data['value'];
            $position = $data['position'];

            // Позиция последней цифры = maxLen - 1 - position
            // Длина частичного = strlen($partial)
            // Начало = maxLen - strlen($partial) - position
            $startPos = $maxLen - strlen($partial) - $position;
            $line = str_repeat(' ', $startPos) . $partial;
            $lines[] = $line;
        }

        // Second dash line (only if more than 1 partial product)
        if (count($partials) > 1) {
            $lines[] = str_repeat('-', $maxLen);
            $lines[] = $finalResult;
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Add two numbers represented as strings
     */
    private function add(string $a, string $b): string
    {
        $result = '';
        $carry = 0;
        $i = strlen($a) - 1;
        $j = strlen($b) - 1;

        while ($i >= 0 || $j >= 0 || $carry > 0) {
            $digitA = $i >= 0 ? (int)$a[$i] : 0;
            $digitB = $j >= 0 ? (int)$b[$j] : 0;

            $sum = $digitA + $digitB + $carry;
            $result = ($sum % 10) . $result;
            $carry = (int)($sum / 10);

            $i--;
            $j--;
        }

        return $result;
    }

    /**
     * Subtract two numbers (a - b), assuming a >= b
     */
    private function subtract(string $a, string $b): string
    {
        $result = '';
        $borrow = 0;
        $i = strlen($a) - 1;
        $j = strlen($b) - 1;

        while ($i >= 0) {
            $digitA = (int)$a[$i];
            $digitB = $j >= 0 ? (int)$b[$j] : 0;

            $diff = $digitA - $digitB - $borrow;

            if ($diff < 0) {
                $diff += 10;
                $borrow = 1;
            } else {
                $borrow = 0;
            }

            $result = $diff . $result;
            $i--;
            $j--;
        }

        // Remove leading zeros if any
        $result = ltrim($result, '0');

        return $result === '' ? '0' : $result;
    }

    /**
     * Multiply string number by single digit
     */
    private function multiplyByDigit(string $num, int $digit): string
    {
        if ($digit === 0) {
            return '0';
        }

        $result = '';
        $carry = 0;

        for ($i = strlen($num) - 1; $i >= 0; $i--) {
            $product = ((int)$num[$i] * $digit) + $carry;
            $result = ($product % 10) . $result;
            $carry = (int)($product / 10);
        }

        if ($carry > 0) {
            $result = $carry . $result;
        }

        return $result;
    }

    /**
     * Multiply two numbers and return partial products + final result
     */
    private function multiplyGetPartials(string $a, string $b): array
    {
        $partials = [];
        $finalResult = '0';

        // Process each digit of b from right to left
        for ($i = strlen($b) - 1; $i >= 0; $i--) {
            $digit = (int)$b[$i];
            $partial = $this->multiplyByDigit($a, $digit);

            // Position offset (how many positions from right)
            $position = strlen($b) - 1 - $i;

            // Store partial with its position
            $partials[] = [
                'value' => $partial,
                'position' => $position,
            ];

            // For calculation: add trailing zeros
            $partial .= str_repeat('0', $position);
            $finalResult = $this->add($finalResult, $partial);
        }

        return [
            'partials' => $partials,
            'result' => $finalResult,
        ];
    }
}

// ============================================
// Main execution for SPOJ submission
// ============================================

if (php_sapi_name() === 'cli' && ! defined('SPOJ_CLI_MODE') && ! defined('PHPUNIT_COMPOSER_INSTALL')) {
    $reader = new InputReader();
    $testCases = $reader->readInt();

    // Validate test cases count according to problem constraints
    if ($testCases < 1 || $testCases > 1000) {
        fwrite(STDERR, "Error: Number of test cases must be between 1 and 1000, got: $testCases\n");
        exit(1);
    }

    for ($t = 0; $t < $testCases; $t++) {
        $line = $reader->readLine();

        // Parse operation
        $operation = match (true) {
            str_contains($line, '+') => '+',
            str_contains($line, '-') => '-',
            str_contains($line, '*') => '*',
            default => throw new InvalidArgumentException("Invalid operation in line: $line")
        };

        // Split by operation (keep as strings for large numbers!)
        [$num1, $num2] = explode($operation, $line);

        try {
            $solver = new ArithmeticFormatter(trim($num1), $operation, trim($num2));
            echo $solver->solve() . "\n\n";
        } catch (InvalidArgumentException $e) {
            fwrite(STDERR, "Error in test case " . ($t + 1) . ": " . $e->getMessage() . "\n");
            exit(1);
        }
    }
}
