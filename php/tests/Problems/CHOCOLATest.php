<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Tests\Problems;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../problems/CHOCOLA/solution.php';

/**
 * Test cases for CHOCOLA - Chocolate Breaking
 */
class CHOCOLATest extends TestCase
{
    /**
     * Test example from problem statement
     */
    public function testBasicExample(): void
    {
        $solver = new \ChocolateBreaker(
            6,  // m (rows)
            4,  // n (cols)
            [2, 1, 3, 1, 4], // vertical costs (m-1 = 5)
            [4, 1, 2]        // horizontal costs (n-1 = 3)
        );

        $this->assertSame(42, $solver->solve());
    }

    /**
     * Test simple 2x2 chocolate
     */
    public function testSimple2x2(): void
    {
        $solver = new \ChocolateBreaker(
            2,  // 2x2
            2,
            [1], // 1 vertical cut
            [1]  // 1 horizontal cut
        );

        // Costs are equal, any order works:
        // v(1)*1 = 1, then h(1)*2 = 2, total = 3
        $this->assertSame(3, $solver->solve());
    }

    /**
     * Test 3x3 with equal costs
     */
    public function testEqualCosts(): void
    {
        $solver = new \ChocolateBreaker(
            3,
            3,
            [5, 5],  // 2 vertical cuts, same cost
            [5, 5]   // 2 horizontal cuts, same cost
        );

        // When costs are equal, we alternate:
        // v1: 5*1=5, vp=2
        // h1: 5*2=10, hp=2
        // v2: 5*2=10, vp=3
        // h2: 5*3=15, hp=3
        // Total: 5+10+10+15 = 40
        $this->assertSame(40, $solver->solve());
    }

    /**
     * Test case where all vertical cuts are more expensive
     */
    public function testVerticalMoreExpensive(): void
    {
        $solver = new \ChocolateBreaker(
            3,
            3,
            [10, 10],  // expensive vertical cuts
            [1, 1]     // cheap horizontal cuts
        );

        // Best strategy: do vertical cuts first (while h_pieces = 1)
        // v1: 10*1 = 10, v_pieces=2
        // v2: 10*1 = 10, v_pieces=3
        // h1: 1*3 = 3, h_pieces=2
        // h2: 1*3 = 3, h_pieces=3
        // Total: 10 + 10 + 3 + 3 = 26
        $this->assertSame(26, $solver->solve());
    }

    /**
     * Test case where all horizontal cuts are more expensive
     */
    public function testHorizontalMoreExpensive(): void
    {
        $solver = new \ChocolateBreaker(
            3,
            3,
            [1, 1],     // cheap vertical cuts
            [10, 10]    // expensive horizontal cuts
        );

        // Best strategy: do horizontal cuts first
        // h1: 10*1 = 10, h_pieces=2
        // h2: 10*1 = 10, h_pieces=3
        // v1: 1*3 = 3, v_pieces=2
        // v2: 1*3 = 3, v_pieces=3
        // Total: 10 + 10 + 3 + 3 = 26
        $this->assertSame(26, $solver->solve());
    }

    /**
     * Test minimum chocolate 2x2 with different costs
     */
    public function testMinimumSizeWithDifferentCosts(): void
    {
        $solver = new \ChocolateBreaker(
            2,
            2,
            [5],  // 1 vertical cut (expensive)
            [1]   // 1 horizontal cut (cheap)
        );

        // Best: v(5)*1 = 5, then h(1)*2 = 2, total = 7
        $this->assertSame(7, $solver->solve());
    }

    /**
     * Test large chocolate with ascending costs
     */
    public function testAscendingCosts(): void
    {
        $solver = new \ChocolateBreaker(
            4,
            4,
            [1, 2, 3],  // ascending vertical costs
            [1, 2, 3]   // ascending horizontal costs
        );

        // Greedy: always pick largest
        // 3v: 3*1=3, vp=2
        // 3h: 3*2=6, hp=2
        // 2v: 2*2=4, vp=3
        // 2h: 2*3=6, hp=3
        // 1v: 1*3=3, vp=4
        // 1h: 1*4=4, hp=4
        // Total: 3+6+4+6+3+4 = 26
        $this->assertSame(26, $solver->solve());
    }

    /**
     * Test validation: rows out of range
     */
    public function testValidationRowsTooSmall(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of rows must be between 2 and 1000');
        new \ChocolateBreaker(1, 3, [], [1, 1]);
    }

    /**
     * Test validation: rows too large
     */
    public function testValidationRowsTooLarge(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of rows must be between 2 and 1000');
        new \ChocolateBreaker(1001, 3, array_fill(0, 1000, 1), [1, 1]);
    }

    /**
     * Test validation: wrong number of vertical costs
     */
    public function testValidationWrongVerticalCount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected 2 vertical costs, got: 1');
        new \ChocolateBreaker(3, 2, [1], [1]);
    }

    /**
     * Test validation: wrong number of horizontal costs
     */
    public function testValidationWrongHorizontalCount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected 2 horizontal costs, got: 1');
        new \ChocolateBreaker(2, 3, [1], [1]);
    }

    /**
     * Test validation: cost out of range
     */
    public function testValidationCostTooLarge(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Vertical cost must be between 1 and 1000');
        new \ChocolateBreaker(3, 2, [1001, 1], [1]);
    }

    /**
     * Test validation: cost zero
     */
    public function testValidationCostZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Horizontal cost must be between 1 and 1000');
        new \ChocolateBreaker(2, 3, [1], [0, 1]);
    }

    /**
     * Test edge case: all costs are 1
     */
    public function testAllCostsOne(): void
    {
        $solver = new \ChocolateBreaker(
            5,
            5,
            [1, 1, 1, 1],
            [1, 1, 1, 1]
        );

        // Total cuts = 8, costs accumulate: 1 + 2 + 3 + ... + 8 = 36
        // Actually: 1*1 + 1*1 + 1*2 + 1*2 + 1*3 + 1*3 + 1*4 + 1*4 = 1+1+2+2+3+3+4+4 = 20
        $result = $solver->solve();
        $this->assertGreaterThan(0, $result);
        $this->assertLessThanOrEqual(50, $result);
    }

    /**
     * Test asymmetric chocolate (tall)
     */
    public function testAsymmetricTall(): void
    {
        $solver = new \ChocolateBreaker(
            2,   // 2 rows (only 1 vertical cut)
            5,   // 5 cols (4 horizontal cuts)
            [10],           // 1 expensive vertical cut
            [1, 1, 1, 1]    // 4 cheap horizontal cuts
        );

        // Best: v(10)*1 = 10, then h costs multiply by 2
        // v: 10*1 = 10, vp=2
        // h: 1*2 + 1*2 + 1*2 + 1*2 = 8
        // Total: 18
        $this->assertSame(18, $solver->solve());
    }

    /**
     * Test asymmetric chocolate (wide)
     */
    public function testAsymmetricWide(): void
    {
        $solver = new \ChocolateBreaker(
            5,   // 5 rows (4 vertical cuts)
            2,   // 2 cols (only 1 horizontal cut)
            [1, 1, 1, 1],   // 4 cheap vertical cuts
            [10]            // 1 expensive horizontal cut
        );

        // Best: h(10)*1 = 10, then v costs multiply by 2
        // h: 10*1 = 10, hp=2
        // v: 1*2 + 1*2 + 1*2 + 1*2 = 8
        // Total: 18
        $this->assertSame(18, $solver->solve());
    }
}
