<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../problems/TRT/solution.php';

/**
 * Tests for TRT - Treats for the Cows
 */
class TRTTest extends TestCase
{
    /**
     * Test example from problem statement
     */
    public function testExampleCase(): void
    {
        $solver = new MaximumRevenueFromTreats([1, 3, 1, 5, 2]);
        $result = $solver->solve();

        $this->assertEquals(43, $result);
    }

    /**
     * Test single treat
     */
    public function testSingleTreat(): void
    {
        $solver = new MaximumRevenueFromTreats([5]);
        $result = $solver->solve();

        // Single treat sold on day 1: 5 × 1 = 5
        $this->assertEquals(5, $result);
    }

    /**
     * Test two treats - ascending order
     */
    public function testTwoTreatsAscending(): void
    {
        $solver = new MaximumRevenueFromTreats([2, 5]);
        $result = $solver->solve();

        // Optimal: take 2 first (2×1=2), then 5 (5×2=10) → 12
        $this->assertEquals(12, $result);
    }

    /**
     * Test two treats - descending order
     */
    public function testTwoTreatsDescending(): void
    {
        $solver = new MaximumRevenueFromTreats([5, 2]);
        $result = $solver->solve();

        // Optimal: take 2 first (2×1=2), then 5 (5×2=10) → 12
        $this->assertEquals(12, $result);
    }

    /**
     * Test two equal treats
     */
    public function testTwoEqualTreats(): void
    {
        $solver = new MaximumRevenueFromTreats([3, 3]);
        $result = $solver->solve();

        // Both strategies give same result: 3×1 + 3×2 = 9
        $this->assertEquals(9, $result);
    }

    /**
     * Test three treats - all equal
     */
    public function testThreeEqualTreats(): void
    {
        $solver = new MaximumRevenueFromTreats([4, 4, 4]);
        $result = $solver->solve();

        // 4×1 + 4×2 + 4×3 = 4 + 8 + 12 = 24
        $this->assertEquals(24, $result);
    }

    /**
     * Test three treats - ascending
     */
    public function testThreeTreatsAscending(): void
    {
        $solver = new MaximumRevenueFromTreats([1, 2, 3]);
        $result = $solver->solve();

        // Optimal: 1×1 + 2×2 + 3×3 = 1 + 4 + 9 = 14
        $this->assertEquals(14, $result);
    }

    /**
     * Test three treats - descending
     */
    public function testThreeTreatsDescending(): void
    {
        $solver = new MaximumRevenueFromTreats([3, 2, 1]);
        $result = $solver->solve();

        // Optimal: take 1 (1×1=1), take 3 (3×2=6), take 2 (2×3=6) → 13
        // Or: take 1 (1×1=1), take 2 (2×2=4), take 3 (3×3=9) → 14
        $this->assertEquals(14, $result);
    }

    /**
     * Test four treats with mixed values
     */
    public function testFourTreatsMixed(): void
    {
        $solver = new MaximumRevenueFromTreats([2, 8, 3, 7]);
        $result = $solver->solve();

        // Expected optimal solution
        $this->assertEquals(57, $result);
    }

    /**
     * Test all minimum values
     */
    public function testAllMinimumValues(): void
    {
        $solver = new MaximumRevenueFromTreats([1, 1, 1, 1]);
        $result = $solver->solve();

        // 1×1 + 1×2 + 1×3 + 1×4 = 1 + 2 + 3 + 4 = 10
        $this->assertEquals(10, $result);
    }

    /**
     * Test all maximum values
     */
    public function testAllMaximumValues(): void
    {
        $solver = new MaximumRevenueFromTreats([1000, 1000, 1000]);
        $result = $solver->solve();

        // 1000×1 + 1000×2 + 1000×3 = 1000 + 2000 + 3000 = 6000
        $this->assertEquals(6000, $result);
    }

    /**
     * Test larger array
     */
    public function testLargerArray(): void
    {
        $solver = new MaximumRevenueFromTreats([1, 5, 2, 8, 3, 7, 4]);
        $result = $solver->solve();

        // Expected result from DP calculation
        $this->assertEquals(142, $result);
    }

    /**
     * Test symmetric array
     */
    public function testSymmetricArray(): void
    {
        $solver = new MaximumRevenueFromTreats([5, 3, 1, 3, 5]);
        $result = $solver->solve();

        // Symmetric case
        $this->assertEquals(51, $result);
    }

    /**
     * Test peak in middle
     */
    public function testPeakInMiddle(): void
    {
        $solver = new MaximumRevenueFromTreats([1, 2, 10, 2, 1]);
        $result = $solver->solve();

        // Save 10 for last: 1×1 + 1×2 + 2×3 + 2×4 + 10×5 = 1+2+6+8+50 = 67
        $this->assertEquals(67, $result);
    }

    /**
     * Test validation: value too small
     */
    public function testValidationValueTooSmall(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Treat values must be positive integers between 1 and 1000');

        new MaximumRevenueFromTreats([1, 0, 3]);
    }

    /**
     * Test validation: value too large
     */
    public function testValidationValueTooLarge(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Treat values must be positive integers between 1 and 1000');

        new MaximumRevenueFromTreats([1, 1001, 3]);
    }

    /**
     * Test validation: negative value
     */
    public function testValidationNegativeValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Treat values must be positive integers between 1 and 1000');

        new MaximumRevenueFromTreats([5, -1, 3]);
    }

    /**
     * Test DP table accessibility
     */
    public function testDpTableAccessible(): void
    {
        $solver = new MaximumRevenueFromTreats([1, 3, 1]);
        $solver->solve();

        $dpTable = $solver->getDpTable();

        $this->assertIsArray($dpTable);
        $this->assertCount(3, $dpTable);
    }

    /**
     * Test DP table diagonal values
     */
    public function testDpTableDiagonalValues(): void
    {
        $values = [2, 5, 3];
        $solver = new MaximumRevenueFromTreats($values);
        $solver->solve();

        $dpTable = $solver->getDpTable();

        // Base case: each treat × n
        $this->assertEquals(2 * 3, $dpTable[0][0]); // 2 × 3 = 6
        $this->assertEquals(5 * 3, $dpTable[1][1]); // 5 × 3 = 15
        $this->assertEquals(3 * 3, $dpTable[2][2]); // 3 × 3 = 9
    }

    /**
     * Test incremental sequence
     */
    public function testIncrementalSequence(): void
    {
        $solver = new MaximumRevenueFromTreats([1, 2, 3, 4, 5]);
        $result = $solver->solve();

        // Optimal: take from ends (1,5,2,4,3)
        $this->assertEquals(55, $result);
    }

    /**
     * Test decremental sequence
     */
    public function testDecrementalSequence(): void
    {
        $solver = new MaximumRevenueFromTreats([5, 4, 3, 2, 1]);
        $result = $solver->solve();

        // Same as incremental due to symmetry
        $this->assertEquals(55, $result);
    }
}
