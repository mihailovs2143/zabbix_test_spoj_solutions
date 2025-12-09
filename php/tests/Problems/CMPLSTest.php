<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Tests\Problems;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../php/problems/CMPLS/solution.php';

class CMPLSTest extends TestCase
{
    // ==================== Basic Examples ====================

    public function testLinearSequence(): void
    {
        // Sequence: 1, 2, 3, 4, 5, 6
        // Differences: 1, 1, 1, 1, 1 (constant)
        // Next: 7, 8, 9
        $solver = new \SequenceCompleter(6, 3, [1, 2, 3, 4, 5, 6]);

        $this->assertEquals([7, 8, 9], $solver->solve());
    }

    public function testQuadraticSequence(): void
    {
        // Sequence: 1, 2, 4, 7, 11, 16, 22, 29
        // Polynomial: 1/2*n^2 - 1/2*n + 1
        // Next: 37, 46
        $solver = new \SequenceCompleter(8, 2, [1, 2, 4, 7, 11, 16, 22, 29]);

        $this->assertEquals([37, 46], $solver->solve());
    }

    public function testAlmostConstantSequence(): void
    {
        // Sequence: 1, 1, 1, 1, 1, 1, 1, 1, 1, 2
        // Jumps at the end
        $solver = new \SequenceCompleter(10, 2, [1, 1, 1, 1, 1, 1, 1, 1, 1, 2]);

        $this->assertEquals([11, 56], $solver->solve());
    }

    public function testConstantSequence(): void
    {
        // Single value: 3
        // Constant polynomial: P(n) = 3
        // Next 10 values: all 3
        $solver = new \SequenceCompleter(1, 10, [3]);

        $this->assertEquals([3, 3, 3, 3, 3, 3, 3, 3, 3, 3], $solver->solve());
    }

    // ==================== Edge Cases ====================

    public function testTwoElements(): void
    {
        // Sequence: 5, 8
        // Linear: difference = 3
        // Next: 11, 14
        $solver = new \SequenceCompleter(2, 2, [5, 8]);

        $this->assertEquals([11, 14], $solver->solve());
    }

    public function testSingleCompletion(): void
    {
        // Complete just one value
        $solver = new \SequenceCompleter(3, 1, [1, 4, 9]);

        $this->assertEquals([16], $solver->solve());
    }

    public function testCubicSequence(): void
    {
        // Cubic: n^3
        // 1, 8, 27, 64, 125
        // Next: 216 (6^3)
        $solver = new \SequenceCompleter(5, 1, [1, 8, 27, 64, 125]);

        $this->assertEquals([216], $solver->solve());
    }

    public function testNegativeValues(): void
    {
        // Sequence with negative differences
        $solver = new \SequenceCompleter(4, 2, [10, 8, 6, 4]);

        $this->assertEquals([2, 0], $solver->solve());
    }

    public function testAllZeros(): void
    {
        // All zeros
        $solver = new \SequenceCompleter(5, 3, [0, 0, 0, 0, 0]);

        $this->assertEquals([0, 0, 0], $solver->solve());
    }

    public function testAlternatingPattern(): void
    {
        // Sequence: 1, -1, 1, -1, 1
        // Quadratic pattern
        $solver = new \SequenceCompleter(5, 2, [1, -1, 1, -1, 1]);

        $result = $solver->solve();
        $this->assertCount(2, $result);
    }

    // ==================== Complex Cases ====================

    public function testLargeLinearSequence(): void
    {
        // 10, 20, 30, 40, 50
        // Arithmetic progression with difference 10
        $solver = new \SequenceCompleter(5, 5, [10, 20, 30, 40, 50]);

        $this->assertEquals([60, 70, 80, 90, 100], $solver->solve());
    }

    public function testSquaresSequence(): void
    {
        // Perfect squares: 1, 4, 9, 16
        // Quadratic polynomial
        $solver = new \SequenceCompleter(4, 2, [1, 4, 9, 16]);

        $this->assertEquals([25, 36], $solver->solve());
    }

    public function testFibonacciLike(): void
    {
        // Not actual Fibonacci (which isn't polynomial)
        // But a polynomial that starts similar
        $solver = new \SequenceCompleter(5, 2, [1, 1, 2, 4, 7]);

        $result = $solver->solve();
        $this->assertCount(2, $result);
        $this->assertIsInt($result[0]);
        $this->assertIsInt($result[1]);
    }

    // ==================== Validation Tests ====================

    public function testValidationSequenceTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Sequence length must be between 1 and 99");

        new \SequenceCompleter(0, 5, []);
    }

    public function testValidationSequenceTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Sequence length must be between 1 and 99");

        new \SequenceCompleter(100, 5, array_fill(0, 100, 1));
    }

    public function testValidationCompletionTooSmall(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Count to complete must be between 1 and 99");

        new \SequenceCompleter(5, 0, [1, 2, 3, 4, 5]);
    }

    public function testValidationSumTooLarge(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Sum of S and C must not exceed 100");

        new \SequenceCompleter(60, 50, array_fill(0, 60, 1));
    }

    public function testValidationWrongSequenceCount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected 5 sequence values, got: 3");

        new \SequenceCompleter(5, 3, [1, 2, 3]);
    }
}
