<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Tests\Problems;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../problems/ARITH/solution.php';

/**
 * Test cases for ARITH - Simple Arithmetics
 */
class ARITHTest extends TestCase
{
    /**
     * Test basic addition
     */
    public function testBasicAddition(): void
    {
        $solver = new \ArithmeticFormatter('12345', '+', '67890');
        $output = $solver->solve();

        $expected = " 12345\n+67890\n------\n 80235";
        $this->assertSame($expected, $output);
    }

    /**
     * Test basic subtraction
     */
    public function testBasicSubtraction(): void
    {
        $solver = new \ArithmeticFormatter('324', '-', '111');
        $output = $solver->solve();

        $expected = " 324\n-111\n----\n 213";
        $this->assertSame($expected, $output);
    }

    /**
     * Test multiplication with multiple digits
     */
    public function testMultiplicationMultipleDigits(): void
    {
        $solver = new \ArithmeticFormatter('325', '*', '4405');
        $output = $solver->solve();

        $lines = explode("\n", $output);

        // Check structure
        $this->assertCount(9, $lines);

        // Check first line (first number)
        $this->assertStringEndsWith('325', $lines[0]);

        // Check second line (operation + second number)
        $this->assertStringEndsWith('*4405', $lines[1]);

        // Check result
        $this->assertSame('1431625', trim($lines[8]));
    }

    /**
     * Test simple multiplication (single digit)
     */
    public function testMultiplicationSingleDigit(): void
    {
        $solver = new \ArithmeticFormatter('1234', '*', '4');
        $output = $solver->solve();

        $expected = "1234\n  *4\n----\n4936";
        $this->assertSame($expected, $output);
    }

    /**
     * Test addition with different lengths
     */
    public function testAdditionDifferentLengths(): void
    {
        $solver = new \ArithmeticFormatter('999', '+', '1');
        $output = $solver->solve();

        $lines = explode("\n", $output);
        $this->assertStringEndsWith('999', $lines[0]);
        $this->assertStringEndsWith('+1', $lines[1]);
        $this->assertStringEndsWith('1000', $lines[3]);
    }

    /**
     * Test subtraction resulting in zero
     */
    public function testSubtractionToZero(): void
    {
        $solver = new \ArithmeticFormatter('100', '-', '100');
        $output = $solver->solve();

        $lines = explode("\n", $output);
        $this->assertStringEndsWith('0', trim($lines[3]));
    }

    /**
     * Test multiplication by zero
     */
    public function testMultiplicationByZero(): void
    {
        $solver = new \ArithmeticFormatter('12345', '*', '0');
        $output = $solver->solve();

        $lines = explode("\n", $output);
        // Should have single partial product of 0
        $this->assertStringContainsString('0', $output);
    }

    /**
     * Test multiplication with zeros in middle
     */
    public function testMultiplicationWithZerosInMiddle(): void
    {
        $solver = new \ArithmeticFormatter('123', '*', '101');
        $output = $solver->solve();

        $lines = explode("\n", $output);
        // Should have 3 partial products: 123, 0, 123
        $this->assertGreaterThan(5, count($lines));
    }

    /**
     * Test large numbers (up to 500 digits)
     */
    public function testLargeNumbers(): void
    {
        $num1 = str_repeat('9', 250);
        $num2 = str_repeat('9', 250);

        $solver = new \ArithmeticFormatter($num1, '+', $num2);
        $output = $solver->solve();

        $lines = explode("\n", $output);
        $result = trim($lines[3]);

        // Result should be 1998...998 (251 digits)
        $this->assertSame(251, strlen($result));
        $this->assertStringStartsWith('1', $result);
    }

    /**
     * Test validation: invalid first number (leading zeros)
     */
    public function testValidationLeadingZeros(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First number must be a positive integer with up to 500 digits');
        new \ArithmeticFormatter('0123', '+', '456');
    }

    /**
     * Test validation: number too long (>500 digits)
     */
    public function testValidationTooLong(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First number must be a positive integer with up to 500 digits');
        new \ArithmeticFormatter(str_repeat('9', 501), '+', '1');
    }

    /**
     * Test validation: subtraction where a < b
     */
    public function testValidationSubtractionNegativeResult(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('first number must be greater than or equal to the second number');
        new \ArithmeticFormatter('100', '-', '200');
    }

    /**
     * Test validation: invalid operation
     */
    public function testValidationInvalidOperation(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid operation');
        $solver = new \ArithmeticFormatter('100', '/', '50');
        $solver->solve(); // Exception thrown here, not in constructor
    }

    /**
     * Test edge case: single digit operations
     */
    public function testSingleDigitOperations(): void
    {
        $solver = new \ArithmeticFormatter('5', '+', '3');
        $output = $solver->solve();
        $this->assertStringEndsWith('8', trim(explode("\n", $output)[3]));

        $solver = new \ArithmeticFormatter('9', '-', '4');
        $output = $solver->solve();
        $this->assertStringEndsWith('5', trim(explode("\n", $output)[3]));

        $solver = new \ArithmeticFormatter('7', '*', '8');
        $output = $solver->solve();
        $this->assertStringEndsWith('56', trim(explode("\n", $output)[3]));
    }

    /**
     * Test formatting: proper alignment
     */
    public function testFormattingAlignment(): void
    {
        $solver = new \ArithmeticFormatter('12', '+', '345');
        $output = $solver->solve();

        $lines = explode("\n", $output);

        // All lines should have same length
        $lengths = array_map('strlen', $lines);
        $this->assertSame($lengths[0], $lengths[1]);
        $this->assertSame($lengths[0], $lengths[2]);
        $this->assertSame($lengths[0], $lengths[3]);
    }

    /**
     * Test zero handling
     */
    public function testZeroHandling(): void
    {
        // Zero + number
        $solver = new \ArithmeticFormatter('0', '+', '123');
        $output = $solver->solve();
        $lines = explode("\n", $output);
        $this->assertStringEndsWith('123', trim($lines[3]));

        // Number - zero
        $solver = new \ArithmeticFormatter('456', '-', '0');
        $output = $solver->solve();
        $lines = explode("\n", $output);
        $this->assertStringEndsWith('456', trim($lines[3]));

        // Number * zero
        $solver = new \ArithmeticFormatter('789', '*', '0');
        $output = $solver->solve();
        $this->assertStringContainsString('0', $output);
    }
}
