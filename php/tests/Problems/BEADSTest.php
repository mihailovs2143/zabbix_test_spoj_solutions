<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Tests\Problems;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../php/problems/BEADS/solution.php';

class BEADSTest extends TestCase
{
    // ==================== Basic Examples ====================

    public function testBasicExampleCab(): void
    {
        // "cab" rotations: "cab", "abc", "bca"
        // Minimum: "abc" starts at position 2
        $solver = new \GlassBeads("cab");

        $this->assertEquals(2, $solver->solve());
        $this->assertEquals(2, $solver->solveModified());
    }

    public function testBasicExampleBaabaa(): void
    {
        // "baabaa" rotations: "baabaa", "aabaab", "abaaba", "baaaba", "aabaab", "abaaba"
        // Minimum: "aabaab" starts at position 2
        $solver = new \GlassBeads("baabaa");

        $this->assertEquals(2, $solver->solve());
        $this->assertEquals(2, $solver->solveModified());
    }

    public function testSingleCharacter(): void
    {
        // Single character - only one rotation
        $solver = new \GlassBeads("a");

        $this->assertEquals(1, $solver->solve());
        $this->assertEquals(1, $solver->solveModified());
    }

    public function testAllSameCharacters(): void
    {
        // "aaa" - all rotations are identical, return first position
        $solver = new \GlassBeads("aaa");

        $this->assertEquals(1, $solver->solve());
        $this->assertEquals(1, $solver->solveModified());
    }

    public function testTwoCharacters(): void
    {
        // "ba" rotations: "ba", "ab"
        // Minimum: "ab" starts at position 2
        $solver = new \GlassBeads("ba");

        $this->assertEquals(2, $solver->solve());
        $this->assertEquals(2, $solver->solveModified());
    }

    // ==================== Edge Cases ====================

    public function testAlreadyMinimal(): void
    {
        // "abc" is already minimal
        $solver = new \GlassBeads("abc");

        $this->assertEquals(1, $solver->solve());
        $this->assertEquals(1, $solver->solveModified());
    }

    public function testReverseAlphabet(): void
    {
        // "dcba" rotations: "dcba", "cbad", "badc", "adcb"
        // Minimum: "adcb" starts at position 4
        $solver = new \GlassBeads("dcba");

        $this->assertEquals(4, $solver->solve());
        $this->assertEquals(4, $solver->solveModified());
    }

    public function testRepeatingPattern(): void
    {
        // "abab" rotations: "abab", "baba", "abab", "baba"
        // Minimum: "abab" starts at position 1 (or 3, but we take first)
        $solver = new \GlassBeads("abab");

        $this->assertEquals(1, $solver->solve());
        $this->assertEquals(1, $solver->solveModified());
    }

    public function testLongerString(): void
    {
        // "algorithm" - test with longer string
        // Let's find manually: all rotations starting with 'a' are candidates
        // "algorithm", "lgorithma", "gorithmal", etc.
        // "algorithm" itself should be minimal among 'a' rotations
        $solver = new \GlassBeads("algorithm");

        $result = $solver->solve();
        $resultModified = $solver->solveModified();

        $this->assertEquals($result, $resultModified);
        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertLessThanOrEqual(9, $result);
    }

    public function testMinimalAtEnd(): void
    {
        // "zzza" rotations: "zzza", "zzaz", "zazz", "azzz"
        // Minimum: "azzz" starts at position 4
        $solver = new \GlassBeads("zzza");

        $this->assertEquals(4, $solver->solve());
        $this->assertEquals(4, $solver->solveModified());
    }

    // ==================== Complex Cases ====================

    public function testMultipleMinimalRotations(): void
    {
        // "abcabc" - rotations: "abcabc", "bcabca", "cabcab", "abcabc", "bcabca", "cabcab"
        // Multiple minimal rotations exist (positions 1 and 4)
        // Should return the first one
        $solver = new \GlassBeads("abcabc");

        $this->assertEquals(1, $solver->solve());
        $this->assertEquals(1, $solver->solveModified());
    }

    public function testLongRepeatingPattern(): void
    {
        // Long string with repeating pattern
        $solver = new \GlassBeads("abababababababab");

        $this->assertEquals(1, $solver->solve());
        $this->assertEquals(1, $solver->solveModified());
    }

    // ==================== Validation Tests ====================

    public function testValidationEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("String length must be between 1 and 10000000");

        new \GlassBeads("");
    }

    public function testValidationUppercaseLetters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("String must contain only lowercase letters");

        new \GlassBeads("ABC");
    }

    public function testValidationNumbers(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("String must contain only lowercase letters");

        new \GlassBeads("abc123");
    }

    public function testValidationSpecialCharacters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("String must contain only lowercase letters");

        new \GlassBeads("ab-cd");
    }
}
