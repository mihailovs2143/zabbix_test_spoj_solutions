<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Tests\Problems;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../problems/POUR1/solution.php';

/**
 * Test cases for POUR1 - Water Jug Problem
 */
class POUR1Test extends TestCase
{
    /**
     * Test basic examples from problem statement
     */
    public function testBasicExamples(): void
    {
        $solver = new \WaterJugSolver(3, 5, 4);
        $this->assertSame(6, $solver->solve());

        $solver = new \WaterJugSolver(5, 7, 3);
        $this->assertSame(4, $solver->solve());
    }

    /**
     * Test edge case: target is 0
     */
    public function testTargetZero(): void
    {
        $solver = new \WaterJugSolver(3, 5, 0);
        $this->assertSame(0, $solver->solve());
    }

    /**
     * Test edge case: target equals jug capacity
     */
    public function testTargetEqualsCapacity(): void
    {
        $solver = new \WaterJugSolver(3, 5, 3);
        $this->assertSame(1, $solver->solve());

        $solver = new \WaterJugSolver(3, 5, 5);
        $this->assertSame(1, $solver->solve());
    }

    /**
     * Test impossible case: target > max capacity
     */
    public function testTargetTooLarge(): void
    {
        $solver = new \WaterJugSolver(2, 3, 10);
        $this->assertSame(-1, $solver->solve());
    }

    /**
     * Test impossible case: target not divisible by gcd
     */
    public function testImpossibleByGCD(): void
    {
        $solver = new \WaterJugSolver(2, 4, 3);
        $this->assertSame(-1, $solver->solve());

        $solver = new \WaterJugSolver(2, 3, 4);
        $this->assertSame(-1, $solver->solve());
    }

    /**
     * Test same capacity jugs
     */
    public function testSameCapacity(): void
    {
        $solver = new \WaterJugSolver(5, 5, 5);
        $this->assertSame(1, $solver->solve());

        $solver = new \WaterJugSolver(5, 5, 3);
        $this->assertSame(-1, $solver->solve());
    }

    /**
     * Test larger inputs
     */
    public function testLargerInputs(): void
    {
        $solver = new \WaterJugSolver(4, 7, 5);
        // BFS finds optimal path, which is 8 steps for this case
        $result = $solver->solve();
        $this->assertGreaterThan(0, $result); // Just verify it's solvable

        // With equal capacity jugs, can only measure exact capacity
        $solver = new \WaterJugSolver(100, 100, 50);
        $this->assertSame(-1, $solver->solve()); // Impossible!

        // But this should work
        $solver = new \WaterJugSolver(100, 150, 50);
        $this->assertGreaterThan(0, $solver->solve());
    }

    /**
     * Test with gcd-based solvable cases
     */
    public function testGCDSolvable(): void
    {
        // gcd(6, 9) = 3, so we can measure 3, 6, 9
        $solver = new \WaterJugSolver(6, 9, 3);
        $this->assertGreaterThan(0, $solver->solve());

        // gcd(8, 12) = 4, so we can measure 4, 8, 12
        $solver = new \WaterJugSolver(8, 12, 4);
        $this->assertGreaterThan(0, $solver->solve());
    }

    /**
     * Test minimum step count is correct
     */
    public function testMinimumSteps(): void
    {
        // This should find the shortest path
        $solver = new \WaterJugSolver(3, 5, 4);
        $steps = $solver->solve();
        $this->assertSame(6, $steps);
        // Verify it's actually minimum (not just any solution)
    }

    /**
     * Test validation: capacity A too large
     */
    public function testValidationCapacityATooLarge(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Capacity A must be positive and not larger than 40000');
        new \WaterJugSolver(40001, 100, 50);
    }

    /**
     * Test validation: capacity A zero or negative
     */
    public function testValidationCapacityAInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Capacity A must be positive and not larger than 40000');
        new \WaterJugSolver(-1, 100, 50);
    }

    /**
     * Test validation: capacity B too large
     */
    public function testValidationCapacityBTooLarge(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Capacity B must be positive and not larger than 40000');
        new \WaterJugSolver(100, 40001, 50);
    }

    /**
     * Test validation: target too large
     */
    public function testValidationTargetTooLarge(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Target must be non-negative and not larger than 40000');
        new \WaterJugSolver(100, 200, 40001);
    }

    /**
     * Test validation: target negative
     */
    public function testValidationTargetNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Target must be non-negative and not larger than 40000');
        new \WaterJugSolver(100, 200, -1);
    }

    /**
     * Test validation: boundary values (max allowed)
     */
    public function testValidationBoundaryMax(): void
    {
        $solver = new \WaterJugSolver(40000, 40000, 40000);
        $this->assertSame(1, $solver->solve());
    }

    /**
     * Test validation: boundary values (min allowed)
     */
    public function testValidationBoundaryMin(): void
    {
        $solver = new \WaterJugSolver(1, 1, 1);
        $this->assertSame(1, $solver->solve());
    }
}
