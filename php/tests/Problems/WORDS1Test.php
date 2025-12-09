<?php

declare(strict_types=1);

namespace ZabbixSPOJ\Tests\Problems;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../problems/WORDS1/solution.php';

/**
 * Test suite for WORDS1 - Play on Words (Eulerian Path)
 */
class WORDS1Test extends TestCase
{
    /**
     * Test example from problem statement
     */
    public function testExample1(): void
    {
        $words = ['acm', 'malform', 'mouse', 'shark', 'kot'];
        $validator = new \WordChainValidator($words);

        // Can't form chain: shark ends with 'k', kot ends with 't'
        $this->assertFalse($validator->solve());
    }

    /**
     * Test valid chain
     */
    public function testExample2(): void
    {
        $words = ['ok', 'kak', 'kak', 'kak'];
        $validator = new \WordChainValidator($words);

        // Can form: ok→kak→kak→kak
        $this->assertTrue($validator->solve());
    }

    /**
     * Test simple valid chain
     */
    public function testSimpleChain(): void
    {
        $words = ['abc', 'cde', 'efg'];
        $validator = new \WordChainValidator($words);

        // abc→cde→efg
        $this->assertTrue($validator->solve());
    }

    /**
     * Test circular chain (Eulerian circuit)
     */
    public function testCircularChain(): void
    {
        $words = ['ab', 'bc', 'ca'];
        $validator = new \WordChainValidator($words);

        // ab→bc→ca (forms cycle)
        $this->assertTrue($validator->solve());
    }

    /**
     * Test single word
     */
    public function testSingleWord(): void
    {
        $words = ['abc'];
        $validator = new \WordChainValidator($words);

        // Single word always valid
        $this->assertTrue($validator->solve());
    }

    /**
     * Test self-loop word
     */
    public function testSelfLoop(): void
    {
        $words = ['aa', 'ab'];
        $validator = new \WordChainValidator($words);

        // aa (self-loop) and ab
        $this->assertTrue($validator->solve());
    }

    /**
     * Test disconnected components
     */
    public function testDisconnected(): void
    {
        $words = ['ab', 'cd'];
        $validator = new \WordChainValidator($words);

        // Two separate components: a→b and c→d
        $this->assertFalse($validator->solve());
    }

    /**
     * Test invalid degrees (too many start points)
     */
    public function testInvalidDegrees(): void
    {
        $words = ['ab', 'ac', 'ad'];
        $validator = new \WordChainValidator($words);

        // 'a' has out-degree 3, but b,c,d each have in-degree 1
        // Can't form valid Eulerian path
        $this->assertFalse($validator->solve());
    }

    /**
     * Test complex valid chain
     */
    public function testComplexChain(): void
    {
        $words = ['abc', 'cda', 'aef', 'fgh', 'hbc'];
        $validator = new \WordChainValidator($words);

        // abc→cda→aef→fgh→hbc (forms valid path)
        $this->assertTrue($validator->solve());
    }

    // ==================== Validation Tests ====================

    /**
     * Test validation: empty array
     */
    public function testValidationEmptyArray(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Words array cannot be empty');

        new \WordChainValidator([]);
    }

    /**
     * Test validation: word too short
     */
    public function testValidationWordTooShort(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Word length must be between 2 and 1000');

        new \WordChainValidator(['a']);
    }

    /**
     * Test validation: word too long
     */
    public function testValidationWordTooLong(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Word length must be between 2 and 1000');

        $longWord = str_repeat('a', 1001);
        new \WordChainValidator([$longWord]);
    }

    /**
     * Test validation: uppercase letters
     */
    public function testValidationUppercase(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must contain only lowercase letters');

        new \WordChainValidator(['ABC']);
    }

    /**
     * Test validation: special characters
     */
    public function testValidationSpecialChars(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must contain only lowercase letters');

        new \WordChainValidator(['ab@c']);
    }

    /**
     * Test validation: numbers
     */
    public function testValidationNumbers(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must contain only lowercase letters');

        new \WordChainValidator(['ab123']);
    }

    /**
     * Test multiple words with one invalid
     */
    public function testValidationMixedValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must contain only lowercase letters');

        new \WordChainValidator(['abc', 'def', 'GHI']);
    }

    /**
     * Test edge case: all same letter
     */
    public function testAllSameLetter(): void
    {
        $words = ['aa', 'aa', 'aa'];
        $validator = new \WordChainValidator($words);

        // All self-loops on same vertex
        $this->assertTrue($validator->solve());
    }

    /**
     * Test branching impossible
     */
    public function testBranchingImpossible(): void
    {
        $words = ['ab', 'bc', 'bd'];
        $validator = new \WordChainValidator($words);

        // b has out-degree 2 (to c and d), but only in-degree 1
        // Need exactly balanced or one +1/-1 pair
        $this->assertFalse($validator->solve());
    }

    /**
     * Test long valid chain
     */
    public function testLongChain(): void
    {
        $words = ['ab', 'bc', 'cd', 'de', 'ef', 'fg', 'gh', 'hi'];
        $validator = new \WordChainValidator($words);

        // Linear chain a→b→c→d→e→f→g→h→i
        $this->assertTrue($validator->solve());
    }
}
