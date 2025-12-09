<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Tests\Problems;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../php/problems/AGGRCOW/solution.php';

class AGGRCOWTest extends TestCase
{
    // ==================== Basic Examples ====================

    public function testBasicExample(): void
    {
        // Example from problem statement
        $positions = [1, 2, 8, 4, 9];
        $solver = new \AggressiveCows(5, 3, $positions);

        $this->assertEquals(3, $solver->solve());
    }

    public function testTwoStallsTwoCows(): void
    {
        // Simplest case: 2 stalls, 2 cows
        $positions = [1, 5];
        $solver = new \AggressiveCows(2, 2, $positions);

        $this->assertEquals(4, $solver->solve());
    }

    public function testThreeStallsTwoCows(): void
    {
        // 3 stalls, 2 cows - should pick extreme positions
        $positions = [1, 3, 9];
        $solver = new \AggressiveCows(3, 2, $positions);

        $this->assertEquals(8, $solver->solve());
    }

    public function testEquallySpacedStalls(): void
    {
        // Stalls at [0, 10, 20, 30], 3 cows
        $positions = [0, 10, 20, 30];
        $solver = new \AggressiveCows(4, 3, $positions);

        $this->assertEquals(10, $solver->solve());
    }

    // ==================== Edge Cases ====================

    public function testUnsortedPositions(): void
    {
        // Algorithm should sort internally
        $positions = [9, 1, 4, 8, 2];
        $solver = new \AggressiveCows(5, 3, $positions);

        $this->assertEquals(3, $solver->solve());
    }

    public function testAllCowsInAllStalls(): void
    {
        // N stalls, N cows - minimum distance is smallest gap
        $positions = [1, 3, 5, 10];
        $solver = new \AggressiveCows(4, 4, $positions);

        $this->assertEquals(2, $solver->solve());
    }

    public function testLargeDistances(): void
    {
        // Large numbers
        $positions = [0, 1000000000];
        $solver = new \AggressiveCows(2, 2, $positions);

        $this->assertEquals(1000000000, $solver->solve());
    }

    public function testClusteredStalls(): void
    {
        // Stalls close together, then one far away
        $positions = [1, 2, 3, 4, 100];
        $solver = new \AggressiveCows(5, 3, $positions);

        // Best: place at [1, 4, 100] → min distance = 3
        // Or: [1, 3, 100] → min distance = 2
        // Or: [1, 50, 100] → not possible (no stall at 50)
        $this->assertEquals(3, $solver->solve());
    }

    public function testManyStallsFewCows(): void
    {
        // 10 stalls, only 2 cows - should use extreme positions
        $positions = [1, 5, 10, 15, 20, 25, 30, 35, 40, 100];
        $solver = new \AggressiveCows(10, 2, $positions);

        $this->assertEquals(99, $solver->solve());
    }

    // ==================== Validation Tests ====================

    public function testValidationStallsTooFew(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Number of stalls must be between 2 and 100000");

        new \AggressiveCows(1, 2, [5]);
    }

    public function testValidationStallsTooMany(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Number of stalls must be between 2 and 100000");

        new \AggressiveCows(1_000_001, 2, array_fill(0, 1_000_001, 1));
    }

    public function testValidationCowsTooFew(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Number of cows must be between 2");

        new \AggressiveCows(5, 1, [1, 2, 3, 4, 5]);
    }

    public function testValidationCowsMoreThanStalls(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Number of cows must be between 2 and 3");

        new \AggressiveCows(3, 4, [1, 2, 3]);
    }

    public function testValidationWrongNumberOfPositions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected 5 stall positions, got: 3");

        new \AggressiveCows(5, 3, [1, 2, 3]);
    }

    public function testValidationPositionTooSmall(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("-1 is out of allowed stall position range");

        new \AggressiveCows(3, 2, [-1, 5, 10]);
    }

    public function testValidationPositionTooLarge(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("is out of allowed stall position range");

        new \AggressiveCows(2, 2, [1, 1_000_000_001]);
    }
}
