<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../problems/PERMUT1/solution.php';

/**
 * Tests for PERMUT1 - Permutations
 */
class PERMUT1Test extends TestCase
{
    /**
     * Test example from problem statement
     */
    public function testExampleCase(): void
    {
        $counter = new PermutationCounter(4, 1);
        $result = $counter->solve();

        $this->assertEquals(3, $result);
    }

    /**
     * Test zero inversions (sorted permutation)
     */
    public function testZeroInversions(): void
    {
        // Only one permutation with 0 inversions: [1,2,3,4]
        $counter = new PermutationCounter(4, 0);
        $result = $counter->solve();

        $this->assertEquals(1, $result);
    }

    /**
     * Test maximum inversions (reverse sorted)
     */
    public function testMaximumInversions(): void
    {
        // For n=4, max inversions = 4*3/2 = 6
        // Only one permutation: [4,3,2,1]
        $counter = new PermutationCounter(4, 6);
        $result = $counter->solve();

        $this->assertEquals(1, $result);
    }

    /**
     * Test impossible case (k too large)
     */
    public function testImpossibleInversions(): void
    {
        // n=4 can have max 6 inversions, k=10 is impossible
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('k must be between 0 and 6');

        new PermutationCounter(4, 10);
    }

    /**
     * Test small case n=2
     */
    public function testSmallCaseN2K0(): void
    {
        // [1,2] - 0 inversions
        $counter = new PermutationCounter(2, 0);
        $result = $counter->solve();

        $this->assertEquals(1, $result);
    }

    public function testSmallCaseN2K1(): void
    {
        // [2,1] - 1 inversion
        $counter = new PermutationCounter(2, 1);
        $result = $counter->solve();

        $this->assertEquals(1, $result);
    }

    /**
     * Test n=3 cases
     */
    public function testN3K0(): void
    {
        // [1,2,3]
        $counter = new PermutationCounter(3, 0);
        $this->assertEquals(1, $counter->solve());
    }

    public function testN3K1(): void
    {
        // [2,1,3], [1,3,2]
        $counter = new PermutationCounter(3, 1);
        $this->assertEquals(2, $counter->solve());
    }

    public function testN3K2(): void
    {
        // [3,1,2], [2,3,1]
        $counter = new PermutationCounter(3, 2);
        $this->assertEquals(2, $counter->solve());
    }

    public function testN3K3(): void
    {
        // [3,2,1]
        $counter = new PermutationCounter(3, 3);
        $this->assertEquals(1, $counter->solve());
    }

    /**
     * Test n=5 with various k values
     */
    public function testN5K2(): void
    {
        $counter = new PermutationCounter(5, 2);
        $result = $counter->solve();

        // Expected: 9 permutations
        $this->assertEquals(9, $result);
    }

    public function testN5K5(): void
    {
        $counter = new PermutationCounter(5, 5);
        $result = $counter->solve();

        // This is a known value from DP computation
        $this->assertEquals(22, $result);
    }

    /**
     * Test larger cases
     */
    public function testN6K3(): void
    {
        $counter = new PermutationCounter(6, 3);
        $result = $counter->solve();

        // Known result
        $this->assertEquals(29, $result);
    }

    public function testN12K0(): void
    {
        // Maximum n with 0 inversions
        $counter = new PermutationCounter(12, 0);
        $result = $counter->solve();

        $this->assertEquals(1, $result);
    }

    public function testN12K1(): void
    {
        // Maximum n with 1 inversion
        $counter = new PermutationCounter(12, 1);
        $result = $counter->solve();

        // There are 11 permutations with exactly 1 inversion
        $this->assertEquals(11, $result);
    }

    /**
     * Test validation: invalid n
     */
    public function testInvalidNTooSmall(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('n must be between 1 and 12');

        new PermutationCounter(0, 5);
    }

    public function testInvalidNTooLarge(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('n must be between 1 and 12');

        new PermutationCounter(13, 5);
    }

    /**
     * Test validation: invalid k
     */
    public function testInvalidKNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('k must be between 0 and 10');

        new PermutationCounter(5, -1);
    }

    public function testInvalidKTooLarge(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('k must be between 0 and 10');

        new PermutationCounter(5, 99);
    }

    /**
     * Test edge case: n=1, k=1 (impossible)
     */
    public function testN1K1(): void
    {
        // n=1 can only have 0 inversions
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('k must be between 0 and 0');

        new PermutationCounter(1, 1);
    }
}
